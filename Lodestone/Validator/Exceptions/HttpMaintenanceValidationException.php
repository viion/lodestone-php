<?php

namespace Lodestone\Validator\Exceptions;

use Lodestone\Validator\HttpRequestValidator;

/**
 * Class HttpMaintenanceValidationException
 * @package Lodestone\Validator
 */
class HttpMaintenanceValidationException extends ValidationException
{
    /**
     * HttpMaintenanceValidationException constructor.
     *
     * @param int $code
     * @param null $previous
     */
    public function __construct($code = HttpRequestValidator::HTTP_SERVICE_NOT_AVAILABLE, $previous = null)
    {
        parent::__construct('Lodestone not available', $code, $previous);
    }
}