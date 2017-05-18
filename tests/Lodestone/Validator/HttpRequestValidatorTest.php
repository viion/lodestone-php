<?php

namespace Lodestone\Tests\Validator;

use PHPUnit\Framework\TestCase;
use Lodestone\Validator\{
    HttpRequestValidator, ValidationException
};

/**
 * Class HttpRequestValidatorTest
 * @package Lodestone\Tests\Validator
 */
class HttpRequestValidatorTest extends TestCase
{
    /**
     * Test is not http error
     */
    public function testIsNotHttpError()
    {
        // given
        $httpCode = 200;
        $validator = new HttpRequestValidator($httpCode, 'test http code');

        // when
        $result = $validator
            ->isNotHttpError()
            ->validate();

        self::assertTrue($result);
    }

    /**
     * Test is not http error with value lower (than) 200
     */
    public function testIsNotHttpErrorWithValueLower200()
    {
        // given
        $httpCode = 199;
        $validator = new HttpRequestValidator($httpCode, 'test http code');

        try {
            // when
            $validator
            ->isNotHttpError()
            ->validate();

            self::fail("Expected ValidationException");
        } catch(ValidationException $vex) {
            // then
            self::assertEquals('Requested page is not available', $vex->getMessage());
        }
    }

    /**
     * Test is not http error with value higher (than) 308
     */
    public function testIsNotHttpErrorWithValueHigher308()
    {
        // given
        $httpCode = 309;
        $validator = new HttpRequestValidator($httpCode, 'test http code');

        try {
            // when
            $validator
            ->isNotHttpError()
            ->validate();

            self::fail("Expected ValidationException");
        } catch(ValidationException $vex) {
            // then
            self::assertEquals('Requested page is not available', $vex->getMessage());
        }
    }
}