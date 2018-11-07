<?php

namespace LogikosTest\Util\Validation\Validator;

use Logikos\Util\Validation\Validator;

class IsInstanceOfTest extends TestCase {
  // Fully Qualified Class Name
  const FQCN = \stdClass::class;

  public function testInstantiate() {
    $v = new Validator\IsInstanceOf(self::FQCN);
    $this->assertSame(self::FQCN, $v->getFQCN());
  }

  public function testInvalidClassNameThrowsException() {
    $this->assertExceptionWillThrow(
        Validator\Exception::class,
        function() { new Validator\IsInstanceOf("Invalid\\ClassName"); }
    );
  }

  public function testValidValues() {
    $validator = new Validator\IsInstanceOf(self::FQCN);
    $this->assertIsValid($validator, new \stdClass);
  }

  public function testInvalidValues() {
    $validator = new Validator\IsInstanceOf(self::FQCN);
    $this->assertInvalid($validator, 'string');
    $this->assertInvalid($validator, '');
    $this->assertInvalid($validator, 123);
    $this->assertInvalid($validator, null);
    $this->assertInvalid($validator, new \DateTime('now'));
  }

  public function testGetMessageAfterValidationFails() {
    $message = "Hey! That isn't the right type!";
    $validator = new Validator\IsInstanceOf(self::FQCN, $message);
    $this->assertSame($message, $validator->getDescription());
  }

  public function testGetDefaultMessage() {
    $message = "Must be an instance of ". self::FQCN;
    $validator = new Validator\IsInstanceOf(self::FQCN, $message);
    $this->assertSame($message, $validator->getDescription());
  }

}