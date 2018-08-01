<?php

namespace Logikos\Util\Tests\Config\Field\Validation\Validator;

use Logikos\Util\Tests\Config\TestCase as ConfigTestCase;
use Logikos\Util\Validation\Validator;

class TestCase extends ConfigTestCase {
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