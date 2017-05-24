<?php
namespace Lodestone\Entities;

use Lodestone\Validator\BaseValidator;

class AbstractEntity{

    /**
     * @var BaseValidator
     */
    protected $validator;

    /**
     * AbstractEntity constructor.
     */
    public function __construct() {
        $this->initializeValidator();
    }

    protected function initializeValidator() {
        $this->validator = new BaseValidator();
    }
}