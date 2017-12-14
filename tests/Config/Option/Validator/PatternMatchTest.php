<?php

namespace Logikos\Util\Tests\Config\Option\Validator;

use Logikos\Util\Config\Option\Validator;
use Logikos\Util\Config\Option\Validator\PatternMatch;
use Logikos\Util\Config\Option\Validator\ValidatorException;
use Logikos\Util\Tests\Config\TestCase;

class PatternMatchTest extends TestCase {
  public function testImpl() {
    $p = '/[a-zA-Z]{3}/';
    $v = new PatternMatch($p);
    $this->assertSame($p, $v->getPattern());
  }

  public function testInvalidPatternThrowsException() {
    $this->assertInvalidPattern('abc');
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



  private function assertIsValid(Validator $validator, $value, $message = null) {
    $this->assertTrue(
        $validator->validate($value),
        $message ?? 'Expected value to be invalid, but it was valid'
    );
  }

  private function assertInvalid(Validator $validator, $value, $message = null) {
    $this->assertFalse(
        $validator->validate($value),
        $message ?? 'Expected value to be valid, but it was not'
    );
  }

  private function assertInvalidPattern($pattern) {
    $this->assertExceptionWillThrow(
        ValidatorException::class,
        function() use($pattern) {
          new PatternMatch($pattern);
        }
    );
  }
}