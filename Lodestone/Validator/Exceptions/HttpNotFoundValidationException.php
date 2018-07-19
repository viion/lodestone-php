<?php

namespace Lodestone\Validator\Exceptions;

use Lodestone\Modules\Validator;

/**
 * Class HttpNotFoundValidationException
 * @package Lodestone\Validator
 */
class HttpNotFoundValidationException extends ValidationException
{
    /**
     * HttpNotFoundValidationException constructor.
     *
     * @param Validator $validator
     * @param int $code
     * @param null $previous
     */
    public function __construct(Validator $validator, $code = Validator::HTTP_NOT_FOUND, $previous = null)
    {
        $message = sprintf(
            '%s not found. Status code: %d. URL: %s',
            $validator->name,
            $validator->object,
            $validator->id
        );
        parent::__construct($message, $code, $previous);
    }
}
