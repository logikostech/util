<?php

namespace Logikos\Util\Tests\Config\Option\Validator;


use Logikos\Util\Config\Option\Validator\Custom as CustomValidator;

class CustomTest extends TestCase {

  public function testImplWithFunction() {
    $validator = new CustomValidator(function($value) {
      return is_int($value);
    });
    $this->assertIsValid($validator, 1);
    $this->assertInvalid($validator, 'foo');
  }

  public function testImplWithStringFunction() {
    $validator = new CustomValidator('is_int');
    $this->assertIsValid($validator, 1);
    $this->assertInvalid($validator, 'foo');
  }

  public function testImplWithCallableArray() {
    $object = new class {
      public function is_int($value) { return is_int($value); }
    };
    $validator = new CustomValidator([$object, 'is_int']);
    $this->assertIsValid($validator, 1);
    $this->assertInvalid($validator, 'foo');
  }

  public function testCanSetAndGetMessage() {
    $validator = new CustomValidator('is_int', 'foo bar');
    $this->assertEquals('foo bar', $validator->getMessage());
  }
}