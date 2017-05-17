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
     * @param $name
     * @return ValidationException
     */
    public static function emptyValidation($name)
    {
        return new ValidationException($name . ' cannot be empty');
    }

    /**
     * @param $name
     * @param $type
     * @return ValidationException
     */
    public static function typeValidation($name, $type)
    {
        return new ValidationException($name . ' is not of type ' . $type);
    }

    /**
     * @param $name
     * @return ValidationException
     */
    public static function integerValidation($name)
    {
        return ValidationException::typeValidation($name, 'Integer');
    }

    /**
     * @param $name
     * @return ValidationException
     */
    public static function stringValidation($name)
    {
        return ValidationException::typeValidation($name, 'String');
    }

    /**
     * @param $name
     * @return ValidationException
     */
    public static function arrayValidation($name)
    {
        return ValidationException::typeValidation($name, 'Array');
    }
}