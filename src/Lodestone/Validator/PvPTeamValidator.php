<?php

namespace Lodestone\Validator;

use Lodestone\Validator\Exceptions\ValidationException;

/**
 * Class PvPTeamValidator
 * @package Lodestone\Validator
 */
class PvPTeamValidator extends BaseValidator
{
    private static $instance = null;
    
    /**
     * @return PvPTeamValidator|null
     */
    public static function getInstance()
    {
        if (null === self::$instance) {
            self::$instance = new PvPTeamValidator();
        }
        
        return self::$instance;
    }
    
    /**
     * PvPTeamValidator constructor.
     */
    protected function __construct()
    {
        parent::__construct();
    }
}
