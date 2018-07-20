<?php

namespace Lodestone\Modules;

use Exception,
    Throwable;
use Lodestone\Modules\Validator;

/**
 * Class Exceptions
 *
 * @package Lodestone\Validator
 */
class Exceptions extends Exception
{
    /**
     * Exceptions constructor.
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
     * @param $validator Validator
     * @return Exceptions
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

        return new Exceptions($message);
    }

    /**
     * @param $name
     * @param $type
     * @return Exceptions
     */
    public static function typeValidation(Validator $validator, $type)
    {
        // convert values to string acceptable values
        $name = $validator->name;
        $object = self::convertArrayToString($validator->object);
        $message = sprintf("%s (%s) is not of type: %s.\n", $name, $object, $type);

        return new Exceptions($message);
    }

    /**
     * @param $validator
     * @return Exceptions
     */
    public static function integerValidation($validator)
    {
        return Exceptions::typeValidation($validator, 'Integer');
    }

    /**
     * @param $validator
     * @return Exceptions
     */
    public static function numericValidation($validator)
    {
        return Exceptions::typeValidation($validator, 'Numeric');
    }

    /**
     * @param $validator
     * @return Exceptions
     */
    public static function stringValidation($validator)
    {
        return Exceptions::typeValidation($validator, 'String');
    }

    /**
     * @param $validator
     * @return Exceptions
     */
    public static function arrayValidation($validator)
    {
        return Exceptions::typeValidation($validator, 'Array');
    }
    
    /**
     * @param $validator
     * @return Exceptions
     */
    public static function objectValidation($validator)
    {
        return Exceptions::typeValidation($validator, 'Object');
    }

    /**
     * @param Validator $validator
     * @return Exceptions
     */
    public static function relativeUrlValidation(Validator $validator)
    {
        $name = $validator->name;
        $object = self::convertArrayToString($validator->object);

        $message = sprintf("%s (%s) is not a relative url.\n", $name, $object);

        return new Exceptions($message);
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