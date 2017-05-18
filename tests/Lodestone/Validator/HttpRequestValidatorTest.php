<?php
namespace Lodestone\Tests\Validator;

use Lodestone\Validator\HttpRequestValidator;
use Lodestone\Validator\ValidationException;
use PHPUnit\Framework\TestCase;

/**
 * Class HttpRequestValidatorTest
 * @package Lodestone\Tests\Validator
 */
class HttpRequestValidatorTest extends TestCase {

  public function testIsNotHttpError() {
    // given
    $httpCode = 200;
    $validator = new HttpRequestValidator($httpCode, 'test http code');

    // when
    $result = $validator
        ->isNotHttpError()
        ->validate();

    self::assertTrue($result);
  }

  public function testIsNotHttpErrorWithValueLower200() {
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

  public function testIsNotHttpErrorWithValueHigher308() {
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