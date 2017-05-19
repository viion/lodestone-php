<?php

namespace Lodestone\Validator;

use Exception,
    Throwable;

/**
 * Class ValidationException
 *
 * @package Lodestone\Validator
 */
class ValidationException extends Exception
{
    /**
     * ValidationException constructor.
     *
     * @param string $message
     * @param int $code
     * @param Throwable|null $previous
     */
    public function __construct($message = '', $code = 0, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }

    /**
     * @param $validator
     * @return string
     */
    public static function notInitialized($validator)
    {
        return new ValidationException($validator->name . ' is not set.');
    }

    /**
     * @param $name
     * @return ValidationException
     */
    public static function emptyValidation($validator)
    {
        $message = sprintf("%s cannot be empty.",
            $validator->name
        );

        return new ValidationException($message);
    }

    /**
     * @param $name
     * @param $type
     * @return ValidationException
     */
    public static function typeValidation($validator, $type)
    {
        $message = sprintf("%s is not of type: %s.",
            $validator->name,
            $type
        );

        return new ValidationException($message);
    }

    /**
     * @param $validator
     * @return ValidationException
     */
    public static function integerValidation($validator)
    {
        return ValidationException::typeValidation($validator, 'Integer');
    }

    /**
     * @param $validator
     * @return ValidationException
     */
    public static function numericValidation($validator)
    {
        return ValidationException::typeValidation($validator, 'Numeric');
    }

    /**
     * @param $validator
     * @return ValidationException
     */
    public static function stringValidation($validator)
    {
        return ValidationException::typeValidation($validator, 'String');
    }

    /**
     * @param $validator
     * @return ValidationException
     */
    public static function arrayValidation($validator)
    {
        return ValidationException::typeValidation($validator, 'Array');
    }
}