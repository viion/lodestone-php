<?php
namespace Lodestone\Validator;

use Exception;
use Throwable;

class ValidationException extends Exception {

    public function __construct($message = "", $code = 0, Throwable $previous = null) {
        parent::__construct($message, $code, $previous);
    }

    public static function emptyValidation($name) {
        return new ValidationException($name . " cannot be empty");
    }

    public static function typeValidation($name, $type) {
        return new ValidationException($name . " is not of type " . $type);
    }

    public static function integerValidation($name) {
        return ValidationException::typeValidation($name, "Integer");
    }

    public static function stringValidation($name) {
        return ValidationException::typeValidation($name, "String");
    }

    public static function arrayValidation($name) {
        return ValidationException::typeValidation($name, "Array");
    }


}