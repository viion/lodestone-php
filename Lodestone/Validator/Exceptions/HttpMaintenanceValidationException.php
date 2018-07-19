<?php

namespace Lodestone\Validator\Exceptions;

use Lodestone\Modules\Validator;

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
    public function __construct($code = Validator::HTTP_SERVICE_NOT_AVAILABLE, $previous = null)
    {
        parent::__construct('Lodestone not available', $code, $previous);
    }
}