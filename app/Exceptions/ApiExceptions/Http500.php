<?php

namespace App\Exceptions\ApiExceptions;

use App\Exceptions\ApiException;

class Http500 extends ApiException
{
    const STATUS = 500;
    const TITLE  = 'Internal Server Error';
}
