<?php

namespace App\Exceptions;

interface ApiExceptionFormatter
{
    public function format(ApiException $exception) : array;
}
