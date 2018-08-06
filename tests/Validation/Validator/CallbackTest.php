<?php

namespace LogikosTest\Util\Validation\Validator;

use Logikos\Util\Validation\Validator\Callback;

class CallbackTest extends TestCase {
  public function testStringFunction() {
    $v = new Callback('is_int', 'some description');
    $this->assertEquals('some description', $v->getDescription());
    $this->assertIsValid($v, 10);
    $this->assertInvalid($v, 'str');
  }

  public function testImplWithFunction() {
    $validator = new Callback(function($value) {
      return is_int($value);
    }, 'description');
    $this->assertIsValid($validator, 1);
    $this->assertInvalid($validator, 'foo');
  }

  public function testImplWithStringFunction() {
    $validator = new Callback('is_int', 'Must be integer');
    $this->assertIsValid($validator, 1);
    $this->assertInvalid($validator, 'foo');
  }

  public function testImplWithCallableArray() {
    $object = new class {
      public function is_int($value) { return is_int($value); }
    };
    $validator = new Callback([$object, 'is_int'], 'Must be integer');
    $this->assertIsValid($validator, 1);
    $this->assertInvalid($validator, 'foo');
  }

}