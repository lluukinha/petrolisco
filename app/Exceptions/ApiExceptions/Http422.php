<?php

namespace App\Exceptions\ApiExceptions;

use App\Exceptions\ApiException;
use App\Exceptions\ApiExceptionFormatter;

class Http422 extends ApiException
{
    use WithFieldsSupport;

    const STATUS = 422;
    const TITLE  = 'invalid';
}
