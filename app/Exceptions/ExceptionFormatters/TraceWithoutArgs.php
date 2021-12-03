<?php

namespace App\Exceptions\ExceptionFormatters;

class TraceWithoutArgs extends TraceFormatter
{
    protected function prettify(array $trace) : array
    {
        $trace = parent::prettify($trace);
        unset($trace['args']);
        return $trace;
    }
}
