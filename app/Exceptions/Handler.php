<?php

namespace App\Exceptions;

use App\Exceptions\ApiException;
use App\Exceptions\ApiExceptions\{ Http401, Http403, Http404, Http500 };
use App\Exceptions\ApiExceptionFormatters;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Report or log an exception.
     *
     * @param  \Throwable  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        // Discard Laravel default behaviour.
        // parent::report($exception);

        $cause = $this->getCause($exception);

        // Handle reportable exceptions (like ApiException instances).
        if (is_callable([$cause, 'report'])) {
            return $cause->report();
        }

        Log::error('', [
            'cause' => $cause->getMessage(),
            'exception' => $cause,
            'stacktrace' => $cause->getTrace(),
        ]);
    }

    private function getCause(Throwable $exception) : \Throwable
    {
        $exceptions = $this->extractPreviousExceptions($exception);
        return $exceptions[count($exceptions)-1];
    }

    private function extractPreviousExceptions(Throwable $exception) : array
    {
        $exceptions = [];
        $previous = $exception;
        while ($previous) {
            $exceptions[] = $previous;
            $previous = $previous->getPrevious();
        }
        return $exceptions;
    }

    // shouldntReport: report all exceptions.
    protected function shouldntReport(Throwable $e)
    {
        return false;
    }

    /**
     * Render an exception into an HTTP response (JSON:API compliant).
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {
        $exception = $this->prepareException($exception);

        $formatter = (config('app.debug'))
            ? new ApiExceptionFormatters\DebugJsonApi()
            : new ApiExceptionFormatters\JsonApi();

        $json = $exception->format($formatter);

        return response()->json($json, $exception->getStatus());
    }

    /**
     * prepareException: convert incoming Exceptions to ApiException.
     */
    protected function prepareException(Throwable $exception) : ApiException
    {
        if ($exception instanceof ApiException) {
            return $exception;
        }

        $exception = parent::prepareException($exception);

        if ($exception instanceof UnauthorizedHttpException) {
            return new Http401('unauthenticated jwt', 401, $exception);
        }
        if ($exception instanceof AuthenticationException) {
            return new Http401('unauthenticated', 401, $exception);
        }
        if ($exception instanceof AuthorizationException) {
            return new Http403('unauthorized', 403, $exception);
        }
        if ($exception instanceof AccessDeniedHttpException) {
            return new Http403('unauthorized', 403, $exception);
        }
        if ($exception instanceof NotFoundHttpException) {
            return new Http404('not-found', 404, $exception);
        }
        if ($exception instanceof ModelNotFoundException) {
            return new Http404('not-found', 404, $exception);
        }
        // Handle unknown Laravel/Symfony HttpExceptions.
        if ($exception instanceof HttpException) {
            $status = $exception->getStatusCode();
            return HttpCustom::makeFromPrevious($status, $exception);
        }

        // This is of last resort.
        // This must not fail to be helpful.
        // Beware of formatters, double-check them if in doubt.
        return new Http500('server-error', 500, $exception);
    }
}

// Special exception to handle uncaught (unknown) Laravel exceptions.
class HttpCustom extends ApiException
{
    public static function makeFromPrevious(int $status, \Throwable $previous)
    {
        return new self($previous->getMessage(), $status, $previous);
    }

    public function getStatus() : int
    {
        return $this->getCode();
    }
}
