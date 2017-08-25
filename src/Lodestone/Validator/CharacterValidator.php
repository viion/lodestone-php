<?php

namespace Lodestone\Validator;

use Lodestone\Validator\Exceptions\ValidationException;

/**
 * Class CharacterValidator
 * @package Lodestone\Validator
 */
class CharacterValidator extends BaseValidator
{
    const VALID_CHARACTER_REGEX = '/^([a-zA-Z\' \-]|\&[^\s]*\;)+\s?$/';

    private static $instance = null;
    
    /**
     * @return CharacterValidator|null
     */
    public static function getInstance() {
        if (null === self::$instance) {
            self::$instance = new CharacterValidator();
        }

        return self::$instance;
    }

    /**
     * CharacterValidator constructor.
     */
    protected function __construct()
    {
        parent::__construct();
    }

    /**
     * @return $this
     */
    public function isValidCharacterName()
    {
        if (!preg_match(self::VALID_CHARACTER_REGEX, $this->object)) {
            $this->errors[] = new ValidationException($this->object . ' is not a valid character name.');
        }

        return $this;
    }
}
