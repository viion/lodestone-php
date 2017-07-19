<?php

namespace Lodestone\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Lodestone\Validator\{
    BaseValidator,
    Exceptions\ValidationException
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
        // when
        $result = BaseValidator::getInstance()
            ->check('', $this->name)
            ->isInitialized()
            ->validate();

        self::assertTrue($result);
    }

    /**
     * test is initialized with null value
     */
    public function testIsInitializedWithNullValue()
    {
        try {
            // when
            BaseValidator::getInstance()
                ->check(null, $this->name)
                ->isInitialized()
                ->validate();

            self::fail('Expected ValidationException');
        } catch (ValidationException $vex) {
            self::assertEquals($this->name . ' is not set.', $vex->getMessage());
        }
    }

    /**
     * Test is not empty
     */
    public function testIsNotEmpty()
    {
        // given
        $string = 'le test';

        // when
        $result = BaseValidator::getInstance()
            ->check($string, $this->name)
            ->isNotEmpty()
            ->validate();

        // then
        self::assertTrue($result);
    }

    public function testIsNotEmptyWithId() {
        // given
        $string = null;
        $id = 1234;

        try {
            // when
            BaseValidator::getInstance()
                ->check($string, $this->name, $id)
                ->isNotEmpty()
                ->validate();

            self::fail('Expected ValidationException');
        } catch(ValidationException $vex) {
            self::assertEquals($this->name . ' cannot be empty for id: ' . $id . '.', $vex->getMessage());
        }
    }

    /**
     * Test is not empty with empty value
     */
    public function testIsNotEmptyWithEmptyValue()
    {
        // given
        $string = '';

        // when
        try {
            BaseValidator::getInstance()
                ->check($string, $this->name)
                ->isNotEmpty()
                ->validate();

            self::fail('Expected ValidationException');
        } catch(ValidationException $vex) {
            // expected
            self::assertEquals($this->name . ' cannot be empty.', $vex->getMessage());
        }
    }

    /**
     * Test is string
     */
    public function testIsString()
    {
        // given
        $string = '';

        // when
        $result = BaseValidator::getInstance()
            ->check($string, $this->name)
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
        $value = 1;

        try {
            // when
            BaseValidator::getInstance()
                ->check($value, $this->name)
                ->isString()
                ->validate();

            self::fail('Expected ValidationException');
        } catch (ValidationException $vex) {
            // then
            $message = sprintf("%s (%s) is not of type: String.\n", $this->name, $value);
            self::assertEquals($message, $vex->getMessage());
        }
    }

    /**
     * Test if an empty string can pass the isString check
     */
    public function testIsStringWithEmptyString()
    {
        // given
        $value = '';

        // when
        $result = BaseValidator::getInstance()
            ->check($value, 'Empty String')
            ->isString()
            ->validate();

        // then
        self::assertTrue($result);
    }

    /**
     * Test if an empty string can pass the isString check
     */
    public function testIsStringWithNullString()
    {
        // given
        $value = null;

        try {
            // when
            BaseValidator::getInstance()
                ->check($value, 'Empty String')
                ->isString()
                ->validate();

            self::fail('Expected ValidationException');
        } catch (ValidationException $vex) {
            // then
            $message = sprintf("Empty String () is not of type: String.\n");
            self::assertEquals($message, $vex->getMessage());
        }
    }

    /**
     * Test if an empty string can pass the isStringOrEmpty check
     */
    public function testIsStringOrEmpty()
    {
        // given
        $value = null;

        // when
        $result = BaseValidator::getInstance()
            ->check($value, 'Empty String')
            ->isStringOrEmpty()
            ->validate();

        self::assertTrue($result);
    }

    /**
     * Test is array
     */
    public function testIsArray()
    {
        // given
        $arr = [];

        // when
        $result = BaseValidator::getInstance()
            ->check($arr, $this->name)
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
        $value = 1;

        try {
            // when
            BaseValidator::getInstance()
                ->check($value, $this->name)
                ->isArray()
                ->validate();

            self::fail('Expected ValidationException');
        } catch (ValidationException $vex) {
            // then
            $message = sprintf("%s (%s) is not of type: Array.\n", $this->name, $value);
            self::assertEquals($message, $vex->getMessage());
        }
    }

    /**
     * Test is integer
     */
    public function testIsInteger()
    {
        // given
        $value = 1;

        // when
        $result = BaseValidator::getInstance()
            ->check($value, $this->name)
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
        $value = '';

        try {
            // when
            BaseValidator::getInstance()
                ->check($value, $this->name)
                ->isInteger()
                ->validate();

            self::fail('Expected ValidationException');
        } catch (ValidationException $vex) {
            // then
            $message = sprintf("%s (%s) is not of type: Integer.\n", $this->name, $value);
            self::assertEquals($message, $vex->getMessage());
        }
    }

    /**
     * Test a validation of multiple values with checkIf
     */
    public function testComplexExampleWithMultipleObjects()
    {
        // given
        $stringValue = 'A String';
        $intValue = 42;
        $arrayValues = ['foo', 'bar'];

        // when
        $result = BaseValidator::getInstance()
            ->check($stringValue, 'String')->isString()
            ->check($intValue, 'Integer')->isInteger()
            ->check($arrayValues, 'Array')->isArray()
            ->validate();

        // then
        self::assertTrue($result);
    }

    /**
     * Test a validation of multiple values with checkIf where one is failing
     */
    public function testComplexExampleWithMultipleObjectsInCaseOfFailure()
    {
        // given
        $stringValue = 'A String';
        $arrayValues = ['foo', 'bar'];

        try {
            // when
            BaseValidator::getInstance()
                ->check($stringValue, 'String')->isString()
                ->check($arrayValues, 'Wrong')->isInteger()
                ->check($arrayValues, 'Array')->isArray()
                ->validate();

            self::fail('Expected ValidationException');
        } catch (ValidationException $vex) {
            // then
            $message = sprintf("%s (%s) is not of type: Integer.\n", 'Wrong', json_encode($arrayValues));
            self::assertEquals($message, $vex->getMessage());
        }
    }

    /**
     * Test if a relative url is passing the validator
     */
    public function testRelativeUrl()
    {
        // given
        $url = '/a/relative/url';

        // when
        $result = BaseValidator::getInstance()
            ->check($url, 'relative url')
            ->isRelativeUrl()
            ->validate();

        // then
        self::assertTrue($result);
    }

    /**
     * Test if an absolute url will fail passing the validator
     */
    public function testRelativeUrlWithAbsoluteUrl()
    {
        // given
        $url = 'https://na.finalfantasxiv.com/a/simple/test';

        try {
            // when
            BaseValidator::getInstance()
                ->check($url, 'Absolute Url')
                ->isRelativeUrl()
                ->validate();

            self::fail('Expected ValidationException');
        } catch(ValidationException $vex) {
            $message = "Absolute Url (https://na.finalfantasxiv.com/a/simple/test) is not a relative url.\n";
            self::assertEquals($message, $vex->getMessage());
        }
    }
}