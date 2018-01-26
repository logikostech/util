<?php

namespace Logikos\Util\Tests\Config\Field;

use Logikos\Util\Config\Field;
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
}