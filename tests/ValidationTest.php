<?php


namespace LogikosTest\Util;


use Logikos\Util\Validation;
use DateTime;

class ValidationTest extends TestCase {
  use ValidationTestingTrait;

  const SOME_VALUE = 'foo';

  public function testIsValidWithNoValidators() {
    $v = new Validation();
    $this->assertValidationPasses($v, null);
    $this->assertValidationPasses($v, '');
    $this->assertValidationPasses($v, new DateTime());
    $this->assertValidationPasses($v, self::SOME_VALUE);
  }

  public function testIsValidWithOneValidator() {
    $v = new Validation();
    $v->addValidator($this->alwaysValidValidator());
    $this->assertValidationPasses($v, self::SOME_VALUE);
  }

  public function testIsValidWithManyValidators() {
    $v = new Validation();
    $v->addValidator(
        $this->alwaysValidValidator(),
        $this->alwaysValidValidator(),
        $this->alwaysValidValidator()
    );
    $this->assertValidationPasses($v, self::SOME_VALUE);
  }

  public function testNotValidWithOneValidator() {
    $v = new Validation();
    $v->addValidator($this->alwaysInvalidValidator());
    $expectedFailedMessageCount = 1;
    $this->assertValidationFails($v, self::SOME_VALUE, $expectedFailedMessageCount);
  }

  public function testNotValidWithManyValidators() {
    $v = new Validation();
    $v->addValidator(
        $this->alwaysInvalidValidator(),
        $this->alwaysInvalidValidator(),
        $this->alwaysInvalidValidator()
    );
    $expectedFailedMessageCount = 3;
    $this->assertValidationFails($v, self::SOME_VALUE, $expectedFailedMessageCount);
  }

  public function testNotValidWithMixedValidators() {
    $v = new Validation();
    $v->addValidator(
        $this->alwaysValidValidator(),
        $this->alwaysInvalidValidator(),
        $this->alwaysValidValidator(),
        $this->alwaysInvalidValidator(),
        $this->alwaysValidValidator()
    );
    $expectedFailedMessageCount = 2;
    $this->assertValidationFails($v, self::SOME_VALUE, $expectedFailedMessageCount);
  }

}