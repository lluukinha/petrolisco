<?php

namespace App\Exceptions\ApiExceptionFormatters;

use App\Exceptions\ApiException;
use App\Exceptions\ApiExceptionFormatter;

class JsonApi implements ApiExceptionFormatter
{
    public function format(ApiException $exception) : array
    {
        $status = $exception->getStatus();
        $title  = $exception->getTitle();
        $detail = $exception->getDetail();
        $meta   = $exception->getMeta();
        $source = $exception->getSource();

        $error = [
            'status' => (string) $status,
            'title'  => (string) $title,
            'detail' => (string) $detail,
        ];

        if (count($source) > 0) {
            $error['source'] = $source;
        }

        if (count($meta) > 0) {
            $error['meta'] = $meta;
        }

        return [
            'errors' => [ $error ],
        ];
    }
}
