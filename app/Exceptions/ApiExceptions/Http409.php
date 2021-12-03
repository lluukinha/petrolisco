<?php

namespace App\Exceptions\ApiExceptions;

use App\Exceptions\ApiException;

class Http409 extends ApiException
{
    use WithFieldSupport;

    const STATUS = 409;
    const TITLE  = 'Conflict';
}
