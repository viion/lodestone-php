<?php

namespace Lodestone\Validator\Exceptions;

use Lodestone\Validator\HttpRequestValidator;

/**
 * Class HttpNotFoundValidationException
 * @package Lodestone\Validator
 */
class HttpNotFoundValidationException extends ValidationException
{
    /**
     * HttpNotFoundValidationException constructor.
     *
     * @param HttpRequestValidator $validator
     * @param int $code
     * @param null $previous
     */
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