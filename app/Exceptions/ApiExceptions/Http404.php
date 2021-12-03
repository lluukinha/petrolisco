<?php

namespace App\Exceptions\ApiExceptions;

use App\Exceptions\ApiException;

class Http404 extends ApiException
{
    use WithFieldSupport;

    const STATUS = 404;
    const TITLE  = 'Not Found';
}
