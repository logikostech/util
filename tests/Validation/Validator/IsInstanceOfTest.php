<?php

namespace Logikos\Util\Tests\Validation\Validator;

use Logikos\Util\Validation\Validator;

class IsInstanceOfTest extends TestCase {
  public function testInstantiate() {
    $fqcn = \stdClass::class;
    $v = new Validator\IsInstanceOf($fqcn);
    $this->assertSame($fqcn, $v->getFQCN());
  }

  public function testInvalidClassNameThrowsException() {
    $this->assertExceptionWillThrow(
        Validator\Exception::class,
        function() { new Validator\IsInstanceOf("Invalid\\ClassName"); }
    );
  }

  public function testValidValues() {
    $validator = new Validator\IsInstanceOf(\stdClass::class);
    $this->assertIsValid($validator, new \stdClass);
  }

  public function testInvalidValues() {
    $validator = new Validator\IsInstanceOf(\stdClass::class);
    $this->assertInvalid($validator, 'string');
    $this->assertInvalid($validator, '');
    $this->assertInvalid($validator, 123);
    $this->assertInvalid($validator, null);
    $this->assertInvalid($validator, new \DateTime('now'));
  }

  public function testGetMessageAfterValidationFails() {
    $message = "Hey! That isn't the right type!";
    $validator = new Validator\IsInstanceOf(\stdClass::class, $message);
    $this->assertSame($message, $validator->getDescription());
  }

  public function testGetDefaultMessage() {
    $message = "Must be an instance of ".\stdClass::class;
    $validator = new Validator\IsInstanceOf(\stdClass::class, $message);
    $this->assertSame($message, $validator->getDescription());
  }

}