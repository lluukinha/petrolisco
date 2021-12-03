<?php

namespace App\Exceptions\ApiExceptions;

use App\Exceptions\ApiException;

class Http401 extends ApiException
{
    use WithFieldSupport;

    const STATUS = 401;
    const TITLE  = 'Unauthorized';
}
