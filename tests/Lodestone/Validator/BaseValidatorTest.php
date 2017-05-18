<?php

namespace Lodestone\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Lodestone\Validator\{
    BaseValidator, ValidationException
};

/**
 * Class BaseValidatorTest
 * @package Lodestone\Tests\Validator
 */
class BaseValidatorTest extends TestCase
{
    private $name = 'testVariable';

    /**
     * test is initialized
     */
    public function testIsInitialized()
    {
        // given
        $validator = new BaseValidator('', $this->name);

        // when
        $result = $validator
            ->isInitialized()
            ->validate();

        self::assertTrue($result);
    }

    /**
     * test is initialized with null value
     */
    public function testIsInitializedWithNullValue()
    {
        // given
        $validator = new BaseValidator(null, $this->name);

        try {
            // when
            $validator
                ->isInitialized()
                ->validate();

            self::fail('Expected ValidationException');
        } catch (ValidationException $vex) {
            self::assertEquals($this->name . ' not set.', $vex->getMessage());
        }
    }

    /**
     * Test is not empty
     */
    public function testIsNotEmpty()
    {
        // given
        $string = 'le test';
        $validator = new BaseValidator($string, $this->name);

        // when
        $result = $validator
            ->isNotEmpty()
            ->validate();

        // then
        self::assertTrue($result);
    }

    /**
     * Test is not empty with empty value
     */
    public function testIsNotEmptyWithEmptyValue()
    {
        // given
        $string = '';
        $validator = new BaseValidator($string, $this->name);

        // when
        try {
            $validator
                ->isNotEmpty()
                ->validate();

            self::fail('Expected ValidationException');
        } catch(ValidationException $vex) {
            // expected
            self::assertEquals($this->name . ' cannot be empty', $vex->getMessage());
        }
    }

    /**
     * Test is string
     */
    public function testIsString()
    {
        // given
        $string = '';
        $validator = new BaseValidator($string, $this->name);

        // when
        $result = $validator
            ->isString()
            ->validate();

        self::assertTrue($result);
    }

    /**
     * Test is string with non string value
     */
    public function testIsStringWithNonStringValue()
    {
        // given
        $int = 1;
        $validator = new BaseValidator($int, $this->name);

        try {
            // when
            $validator
              ->isString()
              ->validate();

            self::fail('Expected ValidationException');
        } catch (ValidationException $vex) {
            // then
            self::assertEquals($this->name . ' is not of type String', $vex->getMessage());
        }
    }

    /**
     * Test is array
     */
    public function testIsArray()
    {
        // given
        $arr = [];
        $validator = new BaseValidator($arr, $this->name);

        // when
        $result = $validator
            ->isArray()
            ->validate();

        self::assertTrue($result);
    }

    /**
     * Test is array with non array value
     */
    public function testIsArrayWithNonArrayValue()
    {
        // given
        $int = 1;
        $validator = new BaseValidator($int, $this->name);

        try {
            // when
            $validator
              ->isArray()
              ->validate();

            self::fail('Expected ValidationException');
        } catch (ValidationException $vex) {
            // then
            self::assertEquals($this->name . ' is not of type Array', $vex->getMessage());
        }
    }

    /**
     * Test is integer
     */
    public function testIsInteger()
    {
        // given
        $int = 1;
        $validator = new BaseValidator($int, $this->name);

        // when
        $result = $validator
            ->isInteger()
            ->validate();

        self::assertTrue($result);
    }

    /**
     * Test is integer with non integer value
     */
    public function testIsIntegerWithNonIntegerValue()
    {
        // given
        $string = '';
        $validator = new BaseValidator($string, $this->name);

        try {
            // when
            $validator
            ->isInteger()
            ->validate();

            self::fail('Expected ValidationException');
        } catch (ValidationException $vex) {
            // then
            self::assertEquals($this->name . ' is not of type Integer', $vex->getMessage());
        }
    }
}