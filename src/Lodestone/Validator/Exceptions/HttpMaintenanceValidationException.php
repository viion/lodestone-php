<?php

namespace Lodestone\Validator;

/**
 * Class HttpMaintenanceValidationException
 * @package Lodestone\Validator
 */
class HttpMaintenanceValidationException extends ValidationException
{
    public function __construct($code = 0, $previous = null)
    {
        parent::__construct('Lodestone not available', $code, $previous);
    }
}