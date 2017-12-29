<?php

namespace Logikos\Util\Tests\Config\Option\Validator;

use Logikos\Util\Config\Option\Validator\PatternMatch;
use Logikos\Util\Config\Option\Validator\ValidatorException;

class PatternMatchTest extends TestCase {

  public function testSetAndGetPattern() {
    $p = '/[a-zA-Z]{3}/';
    $v = new PatternMatch($p);
    $this->assertSame($p, $v->getPattern());
  }

  public function testInvalidPatternThrowsException() {
    $this->assertExceptionWillThrow(
        ValidatorException::class,
        function() { new PatternMatch('abc'); }
    );
  }

  public function testValidValues() {
    $validator = new PatternMatch('/^[a-zA-Z]{3}$/');
    $this->assertIsValid($validator, 'abc');
    $this->assertIsValid($validator, 'aBc');
    $this->assertIsValid($validator, 'ABC');
  }

  public function testInvalidValues() {
    $validator = new PatternMatch('/^[a-zA-Z]{3}$/');
    $this->assertInvalid($validator, 'ab');
    $this->assertInvalid($validator, 'abcd');
    $this->assertInvalid($validator, 123);
    $this->assertInvalid($validator, null);
  }

  public function testGetMessageAfterValidationFails() {
    $message = 'Must be 3 consecutive letters';
    $validator = new PatternMatch('/^[a-zA-Z]{3}$/', $message);
    $this->assertSame($message, $validator->getMessage());
  }
}