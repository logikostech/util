<?php

namespace Logikos\Util\Tests\Config\Field;

use Logikos\Util\Config\Field;
use Logikos\Util\Config\Field\RequiredField;

class RequiredFieldTest extends TestCase {

  public function testSetAndGetName() {
    $field = new RequiredField('username');
    $this->assertEquals('username', $field->getName());
  }

  public function testImplementsField() {
    $this->assertInstanceOf(
        Field::class,
        new RequiredField('username')
    );
  }

  public function testIsRequired() {
    $this->assertTrue((new RequiredField('username'))->isRequired());
  }

  public function testIsInvalidWhenNull() {
    $field = new RequiredField('username');
    $this->assertIsNotValid($field, null, 1);
  }

  public function testIsInvalidWhenEmptyString() {
    $field = new RequiredField('username');
    $this->assertIsNotValid($field, '', 1);
  }

  public function testIsValidWhenNotNullOrEmpty() {
    $field = new RequiredField('username');
    $this->assertIsValid($field, 'foo');
  }

  public function testAddValidationRegexPattern() {
    $field = new RequiredField('username');
    $field->addPattern(
        '/^[a-z]+$/',
        'Lowercase letters only'
    );
    $field->addPattern(
        '/^.{3,20}$/',
        'Length must be between 3 and 30 chars long'
    );

    $this->assertIsValid($field, 'abcde');
    $this->assertIsNotValid($field, 'abc123', 1);
    $this->assertIsNotValid($field, 'aa', 1);
    $this->assertIsNotValid($field, 'a1', 2);
  }
}