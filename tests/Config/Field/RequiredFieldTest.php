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
    $field = new RequiredField;
    $this->assertIsNotValid($field, null, 1);
  }

  public function testIsInvalidWhenEmptyString() {
    $field = new RequiredField;
    $this->assertIsNotValid($field, '', 1);
  }

  public function testIsValidWhenNotNullOrEmpty() {
    $field = new RequiredField;
    $this->assertIsValid($field, 'foo');
  }

  protected function assertIsNotValid(Field $field, $value, $msgCount=1) {
    $result = $field->validate($value);
    $this->assertFalse($result->isValid());
    $this->assertEquals($msgCount, count($result->getMessages()));
  }

  protected function assertIsValid(Field $field, $value) {
    $this->assertTrue($field->validate($value)->isValid());
  }
}