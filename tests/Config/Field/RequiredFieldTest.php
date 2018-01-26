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

  public function testIsInvalidWhenNullOrEmpty() {
    $this->assertIsNotValid(new RequiredField('username'), null, 1);
    $this->assertIsNotValid(new RequiredField('username'), '', 1);
  }

  public function testIsValidWhenNotNullOrEmpty() {
    $this->assertIsValid(new RequiredField('username'), 'foo');
    $this->assertIsValid(new RequiredField('username'), 123);
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

  public function testInvalidRegexPatternThrowsException() {
    $field = new RequiredField('username');
    $this->expectException(Field\Validation\Validator\Exception::class);
    $field->addPattern('invalid pattern', 'desc');
  }

  public function testAddCallable() {
    $field = new RequiredField('age');
    $field->addCallable(
        'is_int',
        "Value must be a real integer"
    );
    $field->addCallable(
        [$this, 'ageCheck'],
        "Way to old!"
    );
    $field->addCallable(
        function($v){ return !is_int($v) || $v >= 18; },
        "Not old enough"
    );
    $this->assertIsValid($field, 25);
    $this->assertIsNotValid($field, 'abc', 1);
    $this->assertIsNotValid($field, 10, 1);
    $this->assertIsNotValid($field, 110, 1);
  }

  public function ageCheck($value) { return !is_int($value) || $value <= 100; }
}