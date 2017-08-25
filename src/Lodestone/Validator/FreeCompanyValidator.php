<?php

namespace Lodestone\Validator;

use Lodestone\Validator\Exceptions\ValidationException;

/**
 * Class FreeCompanyValidator
 * @package Lodestone\Validator
 */
class FreeCompanyValidator extends BaseValidator
{
    private static $instance = null;
    
    /**
     * @return FreeCompanyValidator|null
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new FreeCompanyValidator();
        }
        
        return self::$instance;
    }
    
    /**
     * FreeCompanyValidator constructor.
     */
    protected function __construct()
    {
        parent::__construct();
    }
}
