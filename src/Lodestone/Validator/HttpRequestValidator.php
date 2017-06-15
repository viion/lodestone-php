<?php

namespace Lodestone\Validator;

use Lodestone\Validator\Exceptions\{
    HttpMaintenanceValidationException,
    HttpNotFoundValidationException,
    ValidationException
};

/**
 * Class HttpRequestValidator
 * @package Lodestone\Validator
 */
class HttpRequestValidator extends BaseValidator
{
    const HTTP_OK = 200;
    const HTTP_PERM_REDIRECT = 308;
    const HTTP_SERVICE_NOT_AVAILABLE = 503;
    const HTTP_NOT_FOUND = 404;

    private static $instance = null;

    public static function getInstance() {
        if (null === self::$instance) {
            self::$instance = new HttpRequestValidator();
        }

        return self::$instance;
    }

    /**
    * HttpRequestValidator constructor.
    */
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * A deleted character produces a 404 error
     *
     * @return $this
     */
    public function isFound()
    {
        if ($this->object == self::HTTP_NOT_FOUND) {
            $this->errors[] = new HttpNotFoundValidationException($this);
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
            $this->errors[] = new HttpMaintenanceValidationException();
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
            $this->errors[] = new ValidationException('Requested page is not available');
        }

        return $this;
    }
}