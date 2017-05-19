<?php

namespace Lodestone\Validator;

/**
 * Class BaseValidator
 *
 * @package Lodestone\Validator
 */
class BaseValidator
{
    public $object;
    public $name;
    public $errors;

    /**
     * BaseValidator constructor.
     *
     * @param $object
     * @param $name
     */
    public function __construct($object = null, $name = null)
    {
        $this->check($object, $name);
        $this->errors = [];
    }

  /**
   * @param $object
   * @param $name
   * @return $this
   */
    public function check($object, $name)
    {
        $this->object = $object;
        $this->name = $name;
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
    public function isArray()
    {
        if (!is_array($this->object)) {
            $this->errors[] = ValidationException::arrayValidation($this);
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function validate()
    {
        if (count($this->errors) > 0) {
            // only throw one exception at a time.
            // Maybe this can be improved to stack exceptions
            throw $this->errors[0];
        }

        return true;
    }
}