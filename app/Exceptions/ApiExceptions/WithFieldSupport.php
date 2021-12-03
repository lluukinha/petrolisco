<?php

namespace App\Exceptions\ApiExceptions;

trait WithFieldSupport
{
    protected $field;
    protected $detail;
    protected $title;
    protected $isQueryParameter = false;

    public static function makeForField(string $field,string $title = null,string $detail = null) : self
    {
        $message = static::formatMessageForField($detail);
        $e = new static($message);
        $e->title = $title ?? $e->title;
        $e->field = static::formatFieldName($field);
        $e->detail = $detail;
        return $e;
    }

    public static function makeForQuery(string $field,string $title = null,string $detail = null){
        $error = static::makeForField($field,$title,$detail);
        $error->isQueryParameter = true;
        return $error;
    }

    public function getSource() : array
    {
        return [
            'parameter' => $this->field
        ];
    }

    public function getTitle() : string
    {
        return ($this->title ?? $this->message) ?? static::TITLE;
    }

    protected static function formatMessageForField(string $detail=null) : string
    {
        return trim($detail);
    }

    protected static function formatFieldName(string $input) : string
    {
        return preg_replace('/\./', '/', $input);
    }
}
