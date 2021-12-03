<?php

namespace App\Exceptions\ApiExceptionFormatters;

use App\Exceptions\ApiException;
use App\Exceptions\ApiExceptionFormatter;
use App\Exceptions\ExceptionFormatter;
use App\Exceptions\ExceptionFormatters;
use Exception;
use Throwable;

class DebugJsonApi extends JsonApi
{
    static private $causeTraceLen = 5;
    static private $wrapperTraceLen = 1;
    static private $exceptionOutputEnabled = true;
    static private $traceOutputEnabled = true;
    private $once = false;

    public static function setCauseTraceLen(int $traceLen)
    {
        self::$causeTraceLen = $traceLen;
    }

    public static function setWrapperTraceLen(int $traceLen)
    {
        self::$wrapperTraceLen = $traceLen;
    }

    public static function disableExceptionsOutput()
    {
        self::$exceptionOutputEnabled = false;
    }

    public static function disableTraceOutput()
    {
        self::$traceOutputEnabled = false;
    }

    public function format(ApiException $exception) : array
    {
        $errors = parent::format($exception);
        if ($this->once) {
            return $errors;
        }
        $this->once = true;

        $exceptions = $this->extractPreviousExceptions($exception);
        $cause = array_values(array_slice($exceptions, -1))[0];

        if ($this::$exceptionOutputEnabled) {
            $errors['errors'][0]['meta']['exceptions']
                = array_map(function (Throwable $e) {
                    return (new ExceptionFormatters\MessageAndLocation)->format($e);
                }, $exceptions);
        }

        if ($this::$traceOutputEnabled && $this::$causeTraceLen > 0) {
            $errors['errors'][0]['meta']['trace']['cause']
                = (new ExceptionFormatters\TraceWithArgs(
                    $this::$causeTraceLen))->format($cause);
        }

        if ($this::$traceOutputEnabled && $this::$wrapperTraceLen > 0) {
            $errors['errors'][0]['meta']['trace']['wrapper']
                = (new ExceptionFormatters\TraceWithoutArgs(
                    $this::$wrapperTraceLen))->format($exception);
        }

        return $errors;
    }

    private function extractPreviousExceptions(Exception $exception) : array
    {
        $exceptions = [];
        $previous = $exception;
        while ($previous) {
            $exceptions[] = $previous;
            $previous = $previous->getPrevious();
        }
        return $exceptions;
    }
}
