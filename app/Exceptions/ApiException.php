<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;
use Throwable;

abstract class ApiException extends Exception
{
    // Should be defined by derived classes.
    const STATUS = 500;
    const TITLE  = 'Internal Server Error';
    protected $meta = [];

    public function getStatus() : int
    {
        return static::STATUS;
    }

    public function getTitle() : string
    {
        return static::TITLE;
    }

    public function getSource() : array
    {
        return [];
    }

    public function getDetail() : string
    {
        return $this->getCause()->getMessage();
    }


    /**
     * getMeta: return additional information about the exception.
     */
    public function getMeta() : array
    {
        return $this->meta;
    }

    public function withMeta(array $meta)
    {
        $this->meta = $meta;
        return $this;
    }

    public function getCause() : Throwable
    {
        $cause = $this;
        while (($previous = $cause->getPrevious())) {
            $cause = $previous;
        }
        return $cause;
    }

    public function getMessageStack() : array
    {
        $messages = [];
        $previous = $this;
        do {
            $messages[] = $previous->getMessage();
        } while (($previous = $previous->getPrevious()));
        return $messages;
    }

    public function format(ApiExceptionFormatter $formatter) : array
    {
        $error = $formatter->format($this);
        return $error;
    }

    public function report() : void
    {
        Log::error('', [
            'cause' => $this->getTitle(),
            'detail' => $this->getDetail(),
            'exception' => $this->getCause(),
            'stacktrace' => $this->getTrace(),
        ]);
    }
}
