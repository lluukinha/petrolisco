<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;

use App\Exceptions\ApiExceptions\Http422;

abstract class JSONRequest extends FormRequest
{
    /**
     * default: set default values for the request.
     *   Default values have the lowest precedence.
     */
    public function default() : array
    {
        return [ ];
    }

    /**
     * all: return the given keys from the JSON payload.
     *   Return all the JSON payload by default.
     *   Precedence: default values < query < json
     */
    public function all($keys = null){
        $payload = array_replace_recursive(
            $this->default(),
            parent::query(),
            parent::json()->all()
        );
        if(empty($keys)) {
            return $payload;
        }
        return collect($payload)->only($keys)->toArray();
    }

    /**
     * failedValidation: customize the exception returned in case of
     * validation error.
     */
    protected function failedValidation(Validator $validator)
    {
        throw Http422::makeForFields($validator->errors()->getMessages());
    }
}
