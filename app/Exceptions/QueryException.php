<?php

namespace App\Exceptions;

use Exception;

class QueryException extends Exception
{
    public static function makeFrom(Exception $exception, int $code = 500, string $message = '')
    {
        $caller = self::getCaller();
        return new $caller($message, $code, $exception);
    }

    public static function makeIsRequired(string $property, int $code = 500)
    {
        $message = '`%s` property is required.';
        $message = sprintf($message, $property);
        $caller = self::getCaller();
        return new $caller($message, $code);
    }

    public static function makeInvalidParametersClassType(string $class, int $code = 500) : self
    {
        $message = 'This query must be constructed using an instance of `%s`.';
        $message = sprintf($message, $class);
        return new self($message, $code);
    }

    public static function makeQueryMustBeCallable(string $class, int $code = 500) : self
    {
        $message = '`%s` query must implement `__invoke`.';
        $message = sprintf($message, $class);
        return new self($message, $code);
    }

    public static function makeParametersClassDoesNotExist(string $class, int $code = 500) : self
    {
        $message = '`%s` Parameters class does not exist.';
        $message = sprintf($message, $class);
        return new self($message, $code);
    }

    public static function makeMustEndWithSuffix(string $baseclass, string $suffix, string $class, int $code = 500) : self
    {
        $message = '%s derived class must end with `%s` suffix, got `%s`.';
        $message = sprintf($message, $baseclass, $suffix, $class);
        return new self($message, $code);
    }

    public static function makeQueryClassDoesNotExist(string $class, int $code = 500) : self
    {
        $message = '`%s` query class does not exist.';
        $message = sprintf($message, $class);
        return new self($message, $code);
    }

    protected static function getCaller() : string
    {
        return get_called_class();
    }
}
