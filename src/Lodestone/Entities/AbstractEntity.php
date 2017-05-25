<?php

namespace Lodestone\Entities;

use Lodestone\Validator\BaseValidator;

/**
 * Class AbstractEntity
 *
 * @package Lodestone\Entities
 */
class AbstractEntity
{
    /**
     * @var BaseValidator
     */
    protected $validator;

    /**
     * AbstractEntity constructor.
     */
    public function __construct()
    {
        $this->initializeValidator();
    }

    /**
     * @return $this
     */
    protected function initializeValidator()
    {
        $this->validator = new BaseValidator();
        return $this;
    }
}