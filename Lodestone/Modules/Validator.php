<?php

namespace Lodestone\Modules;

use Lodestone\Validator\Exceptions\{
    HttpMaintenanceValidationException,
    HttpNotFoundValidationException,
    ValidationException
};

class Validator
{
    const URL_REGEX = '/^https?:\/\//';
    const VALID_CHARACTER_REGEX = '/^([a-zA-Z\' \-]|\&[^\s]*\;)+\s?$/';
    const HTTP_OK = 200;
    const HTTP_PERM_REDIRECT = 308;
    const HTTP_SERVICE_NOT_AVAILABLE = 503;
    const HTTP_NOT_FOUND = 404;

    public $object;
    public $name;

    /**
     * @var int
     */
    public $id;
    
    private static $instance = null;

    public static function getInstance() {
        if (null === self::$instance) {
            self::$instance = new Validator();
        }

        return self::$instance;
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
     * @return $this
     */
    public function isNotEmpty()
    {
        if (empty($this->object)) {
            throw ValidationException::emptyValidation($this);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function isInteger()
    {
        if (!is_int($this->object)) {
            throw ValidationException::integerValidation($this);
        }

        return $this;
    }

    /**
     * @return $this
     */
    public function isNumeric()
    {
        if (!is_numeric($this->object)) {
            throw ValidationException::numericValidation($this);
        }

        return $this;
    }
    
    /**
     * @return $this
     */
    public function isString()
    {
        if (!is_string($this->object)) {
            throw ValidationException::stringValidation($this);
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
            throw ValidationException::arrayValidation($this);
        }

        return $this;
    }
    
    /**
     * @return $this
     */
    public function isObject()
    {
        if (!is_object($this->object)) {
            throw ValidationException::objectValidation($this);
        }
        
        return $this;
    }

    /**
     * @return $this
     */
    public function isRelativeUrl()
    {
        if (preg_match(self::URL_REGEX, $this->object)) {
            throw ValidationException::relativeUrlValidation($this);
        }

        return $this;
    }
    
    /**
     * @return $this
     */
    public function isValidCharacterName()
    {
        if (!preg_match(self::VALID_CHARACTER_REGEX, $this->object)) {
            throw new ValidationException($this->object . ' is not a valid character name.');
        }

        return $this;
    }
    
    /**
     * A deleted character produces a 404 error
     *
     * @return $this
     */
    public function isFound()
    {
        if ($this->object == self::HTTP_NOT_FOUND) {
            throw new HttpNotFoundValidationException($this);
        }

        return $this;
    }

    /**
     * When the lodestone is on maintenance, it returns 503 for all pages
     *
     * @return $this
     */
    public function isNotMaintenance()
    {
        if ($this->object == self::HTTP_SERVICE_NOT_AVAILABLE) {
            throw new HttpMaintenanceValidationException();
        }

        return $this;
    }

    /**
     * 2XX and 3XX Status codes are for successful connections or redirects (so no error)
     *
     * @see https://de.wikipedia.org/wiki/HTTP-Statuscode
     * @return $this
     */
    public function isNotHttpError()
    {
        if ($this->object < self::HTTP_OK || $this->object > self::HTTP_PERM_REDIRECT) {
            throw new ValidationException('Requested page '.$this->id.' is not available');
        }

        return $this;
    }
}
