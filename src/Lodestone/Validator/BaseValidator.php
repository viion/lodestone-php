<?php

namespace Lodestone\Validator;

use Lodestone\Validator\Exceptions\ValidationException;

/**
 * Class BaseValidator
 *
 * @package Lodestone\Validator
 */
class BaseValidator
{
    const URL_REGEX = '/^https?:\/\//';

    public $object;
    public $name;
    public $errors;

    /**
     * @var int
     */
    public $id;

    private static $instance = null;

    public static function getInstance() {
        if (null === self::$instance) {
            self::$instance = new BaseValidator();
        }

        return self::$instance;
    }

    /**
     * BaseValidator constructor.
     *
     */
    protected function __construct()
    {
        $this->errors = [];
    }


    /**
     * Is not allowed for a singleton
     */
    protected function __clone() {}

    /**
   * @param $object
   * @param $name
   * @param $id integer (optional)
   * @return $this
   */
    public function check($object, $name, $id = null)
    {
        $this->object = $object;
        $this->name = $name;
        $this->id = $id;
        return $this;
    }

    /**
     * @return array
     */
    public function validateAndFetchErrors()
    {
        return $this->errors;
    }

    /**
     * @return $this
     */
    public function isInitialized()
    {
        if (is_null($this->object)) {
            $this->errors[] = ValidationException::notInitialized($this);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function isNotEmpty()
    {
        if (empty($this->object)) {
            $this->errors[] = ValidationException::emptyValidation($this);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function isInteger()
    {
        if (!is_int($this->object)) {
            $this->errors[] = ValidationException::integerValidation($this);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function isNumeric()
    {
        if (!is_numeric($this->object)) {
            $this->errors[] = ValidationException::numericValidation($this);
        }

        return $this;
    }
    
    /**
     * @return $this
     */
    public function isString()
    {
        if (!is_string($this->object)) {
            $this->errors[] = ValidationException::stringValidation($this);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function isStringOrEmpty()
    {
        if (empty($this->object)) {
            return $this;
        }

       return $this->isString();
    }

    /**
     * @return $this
     */
    public function isArray()
    {
        if (!is_array($this->object)) {
            $this->errors[] = ValidationException::arrayValidation($this);
        }

        return $this;
    }
    
    /**
     * @return $this
     */
    public function isObject()
    {
        if (!is_object($this->object)) {
            $this->errors[] = ValidationException::objectValidation($this);
        }
        
        return $this;
    }

    /**
     * @return $this
     */
    public function isRelativeUrl()
    {
        if (preg_match(self::URL_REGEX, $this->object)) {
            $this->errors[] = ValidationException::relativeUrlValidation($this);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        $errors = $this->errors;
        $this->errors = [];

        if (count($errors) > 0) {
            // only throw one exception at a time.
            // Maybe this can be improved to stack exceptions
            throw $errors[0];
        }

        return true;
    }
}
