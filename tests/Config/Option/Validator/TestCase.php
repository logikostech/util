<?php

namespace Logikos\Util\Tests\Config\Option\Validator;


use Logikos\Util\Config\Option\Validator;

class TestCase extends \Logikos\Util\Tests\Config\TestCase {


  protected function assertIsValid(Validator $validator, $value, $message = null) {
    $this->assertTrue(
        $validator->validate($value),
        $message ?? 'Expected value to be invalid, but it was valid'
    );
  }

  protected function assertInvalid(Validator $validator, $value, $message = null) {
    $this->assertFalse(
        $validator->validate($value),
        $message ?? 'Expected value to be valid, but it was not'
    );
  }
}