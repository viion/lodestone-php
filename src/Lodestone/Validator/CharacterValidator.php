<?php

namespace Lodestone\Validator;

/**
 * Class CharacterValidator
 * @package Lodestone\Validator
 */
class CharacterValidator extends BaseValidator
{
    const VALID_CHARACTER_REGEX = '/^[a-zA-Z\' ]+\s?$/';

    /**
     * CharacterValidator constructor.
     * @param $object
     * @param $name
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return $this
     */
    public function isValidCharacerName()
    {
        if (!preg_match(self::VALID_CHARACTER_REGEX, $this->object)) {
            $this->errors[] = new ValidationException($this->object . ' is not a valid character name.');
        }

        return $this;
    }
}