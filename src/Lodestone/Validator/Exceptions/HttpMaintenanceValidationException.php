<?php

namespace Lodestone\Validator\Exceptions;

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
    public function __construct($code = 0, $previous = null)
    {
        parent::__construct('Lodestone not available', $code, $previous);
    }
}