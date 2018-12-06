<?php

namespace LogikosTest\Util\Validation\Validator;

use Logikos\Util\Validation\Validator;

class RegexTest extends TestCase {

  public function testSetAndGetPattern() {
    $p = '/[a-zA-Z]{3}/';
    $v = new Validator\Regex($p);
    $this->assertSame($p, $v->getPattern());
  }

  public function testHasDefaultDescription() {
    $p = '/[a-zA-Z]{3}/';
    $v = new Validator\Regex($p);
    $this->assertNotEmpty($v->getDescription());
  }

  public function testInvalidPatternThrowsException() {
    $this->assertExceptionWillThrow(
        Validator\Exception::class,
        function() { new Validator\Regex('abc'); }
    );
  }

  public function testValidValues() {
    $validator = new Validator\Regex('/^[a-zA-Z]{3}$/');
    $this->assertIsValid($validator, 'abc');
    $this->assertIsValid($validator, 'aBc');
    $this->assertIsValid($validator, 'ABC');
  }

  public function testInvalidValues() {
    $validator = new Validator\Regex('/^[a-zA-Z]{3}$/');
    $this->assertInvalid($validator, 'ab');
    $this->assertInvalid($validator, 'abcd');
    $this->assertInvalid($validator, 123);
    $this->assertInvalid($validator, null);
  }

  public function testGetMessageAfterValidationFails() {
    $message = 'Must be 3 consecutive letters';
    $validator = new Validator\Regex('/^[a-zA-Z]{3}$/', $message);
    $this->assertSame($message, $validator->getDescription());
  }
}