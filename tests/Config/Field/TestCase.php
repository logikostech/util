<?php

namespace LogikosTest\Util\Config\Field;

use Logikos\Util\Config\Field;
use LogikosTest\Util\Config\TestCase as ConfigTestCase;
use LogikosTest\Util\ValidationTestingTrait;

class TestCase extends ConfigTestCase {

  use ValidationTestingTrait;

  protected function assertFieldIsNotValid(Field $field, $value, $msgCount=1) {
    $result = $field->validate($value);
    $this->assertFalse($result->isValid());
    $this->assertEquals($msgCount, count($result->getMessages()));
  }

  protected function assertFieldIsValid(Field $field, $value) {
    $this->assertTrue($field->validate($value)->isValid());
  }
}