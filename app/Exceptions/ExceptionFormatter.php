<?php

namespace App\Exceptions;

use Throwable;

interface ExceptionFormatter
{
    public function format(Throwable $exception) : array;
}
