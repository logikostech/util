<?php

namespace Logikos\Util\Tests\Config\Field;


use Logikos\Util\Config\Field\OptionalField;

class OptionalFieldTest extends TestCase {
  public function testIsRequiredShouldBeFalse() {
    $this->assertFalse((new OptionalField('foo'))->isRequired());
  }

  public function testIsInvalidWhenNull() {
    $field = new OptionalField('username');
    $this->assertIsValid($field, null);
  }

  public function testIsInvalidWhenEmptyString() {
    $field = new OptionalField('username');
    $this->assertIsValid($field, '');
  }
}