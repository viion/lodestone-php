<?php

namespace Lodestone\Validator\Exceptions;

use Exception,
    Throwable;
use Lodestone\Validator\BaseValidator;
use Lodestone\Validator\CharacterValidator;

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
     * @param $validator BaseValidator|CharacterValidator
     * @return ValidationException
     */
    public static function emptyValidation($validator)
    {
        if ($validator->id != null) {
            $message = sprintf("%s cannot be empty for id: %d.",
                $validator->name,
                $validator->id
            );
        } else {
            $message = sprintf("%s cannot be empty.",
                $validator->name
            );
        }

        return new ValidationException($message);
    }

    /**
     * @param $name
     * @param $type
     * @return ValidationException
     */
    public static function typeValidation(BaseValidator $validator, $type)
    {
        // convert values to string acceptable values
        $name = $validator->name;
        $object = self::convertArrayToString($validator->object);

        $message = sprintf("%s (%s) is not of type: %s.\n", $name, $object, $type);

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
    
    /**
     * @param $validator
     * @return ValidationException
     */
    public static function objectValidation($validator)
    {
        return ValidationException::typeValidation($validator, 'Object');
    }

    /**
     * @param BaseValidator $validator
     * @return ValidationException
     */
    public static function relativeUrlValidation(BaseValidator $validator)
    {
        $name = $validator->name;
        $object = self::convertArrayToString($validator->object);

        $message = sprintf("%s (%s) is not a relative url.\n", $name, $object);

        return new ValidationException($message);
    }

    /**
     * Convert an array into a string
     *
     * @param $object
     * @return string
     */
    private static function convertArrayToString( $object)
    {
            return is_array($object) ? json_encode($object) : $object;
    }
}