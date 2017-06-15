<?php

namespace Lodestone\Validator;

/**
 * Class HttpNotFoundValidationException
 * @package Lodestone\Validator
 */
class HttpNotFoundValidationException extends ValidationException
{
    public function __construct(HttpRequestValidator $validator, $code = 0, $previous = null)
    {
        $message = sprintf(
            '%s not found. Status code: %d',
            $validator->name,
            $validator->object
        );
        parent::__construct($message, $code, $previous);
    }
}