<?php

namespace Lodestone\Validator;

use Lodestone\Validator\Exceptions\ValidationException;

/**
 * Class LinkshellValidator
 * @package Lodestone\Validator
 */
class LinkshellValidator extends BaseValidator
{
    private static $instance = null;
    
    /**
     * @return LinkshellValidator|null
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new LinkshellValidator();
        }
        
        return self::$instance;
    }
    
    /**
     * LinkshellValidator constructor.
     */
    protected function __construct()
    {
        parent::__construct();
    }
}
