<?php

namespace Logikos\Util\Tests\Config\Field;

use Logikos\Util\Config\Field;
use Logikos\Util\Config\Field\RequiredField;
use Logikos\Util\Tests\Config\TestCase;

class RequiredFieldTest extends TestCase {
  public function testImplementsField() {
    $this->assertInstanceOf(Field::class, new RequiredField);
  }

  public function testIsInvalidWhenNull() {
    $this->assertValid(new RequiredField, null);
  }

  protected function assertValid(Field $field, $value) {
    $this->assertTrue($field->validate($value)->isValid());
  }
}