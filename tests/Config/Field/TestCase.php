<?php

namespace Logikos\Util\Tests\Config\Field;

use Logikos\Util\Config\Field;
use Logikos\Util\Config\Field\Validation\Validator;
use Logikos\Util\Tests\Config\TestCase as ConfigTestCase;

class TestCase extends ConfigTestCase {


  protected function assertIsNotValid(Field $field, $value, $msgCount=1) {
    $result = $field->validate($value);
    $this->assertFalse($result->isValid());
    $this->assertEquals($msgCount, count($result->getMessages()));
  }

  protected function assertIsValid(Field $field, $value) {
    $this->assertTrue($field->validate($value)->isValid());
  }

  /** @return Validator */
  protected function alwaysInvalidValidator() {
    return new class implements Validator {
      public function validate($value):bool { return false; }
      public function getDescription() { return 'invalid'; }
    };
  }
}