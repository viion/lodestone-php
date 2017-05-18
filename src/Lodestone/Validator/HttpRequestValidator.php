<?php

namespace Lodestone\Validator;

/**
 * Created by IntelliJ IDEA.
 * User: Stefan
 * Date: 18.05.2017
 * Time: 11:28
 *
 * Class HttpRequestValidator
 * @package Lodestone\Validator
 */
class HttpRequestValidator extends BaseValidator
{
    private static $HTTP_OK = 200;
    private static $HTTP_PERM_REDIRECT = 308;

    /**
    * HttpRequestValidator constructor.
    * @param $object
    * @param $name
    */
    public function __construct($object, $name)
    {
        parent::__construct($object, $name);
    }

    /**
    * @return $this
    */
    public function isNotHttpError()
    {
        // see https://de.wikipedia.org/wiki/HTTP-Statuscode for informations about the used status codes
        // TLDR: 2XX and 3XX Status codes are for successful connections or redirects (so no error)
        if ($this->object < self::$HTTP_OK || $this->object > self::$HTTP_PERM_REDIRECT) {
            $this->errors[] = new ValidationException('Requested page is not available');
        }

        return $this;
    }
}