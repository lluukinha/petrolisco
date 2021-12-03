<?php

namespace App\Exceptions\ApiExceptions;

use App\Exceptions\ApiExceptionFormatter;

trait WithFieldsSupport
{
    use WithFieldSupport;

    protected $errors = [];

    public static function makeForFields(array $errors, int $code = 0, \Throwable $previous = null)
    {
        $exception = new static('', $code, $previous);
        $exception->setErrors($errors);
        return $exception;
    }

    protected function setErrors(array $errors)
    {
        $this->errors = $errors;
    }

    public function format(ApiExceptionFormatter $formatter) : array
    {
        if (count($this->errors) == 0) {
            return parent::format($formatter);
        }

        $errors = [];
        foreach ($this->errors as $field => $messages) {
            foreach ($messages as $message) {
                $exception = static::makeForField($field,$message);
                $formated = $formatter->format($exception);
                $errors = array_merge_recursive($errors, $formated);
            }
        }
        return $errors;
    }
}
