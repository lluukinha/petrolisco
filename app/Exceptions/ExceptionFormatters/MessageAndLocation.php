<?php

namespace App\Exceptions\ExceptionFormatters;

use App\Exceptions\ExceptionFormatter;
use Throwable;

class MessageAndLocation implements ExceptionFormatter
{
    public function format(Throwable $exception) : array
    {
        $file = str_replace(app()->basePath(), '', $exception->getFile());
        $formatted = [];
        $formatted['file'] = $file;
        $formatted['line'] = $exception->getLine();
        $formatted['code'] = $exception->getCode();
        $formatted['message'] = $exception->getMessage();
        return $formatted;
    }
}
