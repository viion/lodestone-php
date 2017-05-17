<?php

namespace Lodestone\Validator;

/**
 * Class BaseValidator
 *
 * @package Lodestone\Validator
 */
class BaseValidator
{
    protected $object;
    protected $name;
    protected $errors;

    /**
     * BaseValidator constructor.
     *
     * @param $object
     * @param $name
     */
    public function __construct($object, $name)
    {
        $this->object = $object;
        $this->name = $name;
        $this->errors = [];
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
        if (!$this->object) {
            $this->errors[] = new ValidationException($this->name . ' not set, please run url parser first');
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function isNotEmpty()
    {
        if (!empty($this->object)) {
            $this->errors[] = ValidationException::emptyValidation($this->name);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function isInteger()
    {
        if (!is_int($this->object)) {
            $this->errors[] = ValidationException::integerValidation($this->name);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function isString()
    {
        if (!is_string($this->object)) {
            $this->errors[] = ValidationException::stringValidation($this->name);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function isArray()
    {
        if (!is_array($this->object)) {
            $this->errors[] = ValidationException::arrayValidation($this->name);
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