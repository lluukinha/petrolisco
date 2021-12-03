<?php

namespace App\Exceptions\ApiExceptions;

use App\Exceptions\ApiException;

class Http403 extends ApiException
{
    const STATUS = 403;
    const TITLE  = 'Forbidden';
}
