<?php

namespace Logikos\Util\Tests\Config\Field;

use Logikos\Util\Config\ConfigException;
use Logikos\Util\Config\Field as FieldInterface;
use Logikos\Util\Config\Field\Field;
use Logikos\Util\Validation\Validator;

class FieldTest extends TestCase {

  public function testSetAndGetName() {
    $field = new Field('username');
    $this->assertEquals('username', $field->getName());
  }

  public function testImplementsField() {
    $this->assertInstanceOf(
        FieldInterface::class,
        new Field('username')
    );
  }

  public function testAddValidationRegexPattern() {
    $field = new Field('username');
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
    $field = new Field('username');
    $this->expectException(Validator\Exception::class);
    $field->addPattern('invalid pattern', 'desc');
  }

  public function testAddCallable() {
    $field = new Field('age');
    $field->addCallable(
        'is_int',
        "Value must be a real integer"
    );
    $field->addCallable(
        [$this, 'ageCheck'],
        "Way to old!"
    );
    $field->addCallable(
        function ($v) { return !is_int($v) || $v >= 18; },
        "Not old enough"
    );
    $this->assertIsValid($field, 25);
    $this->assertIsNotValid($field, 'abc', 1);
    $this->assertIsNotValid($field, 10, 1);
    $this->assertIsNotValid($field, 110, 1);
  }
  public function ageCheck($value) { return !is_int($value) || $value <= 100; }


  public function testIsRequired() {
    $this->assertTrue((new Field('username'))->isRequired());
  }

  public function testIsInvalidWhenNullOrEmpty() {
    $this->assertIsNotValid(new Field('username'), null, 1);
    $this->assertIsNotValid(new Field('username'), '', 1);
  }

  public function testIsValidWhenNotNullOrEmpty() {
    $this->assertIsValid(new Field('username'), 'foo');
    $this->assertIsValid(new Field('username'), 123);
  }

  public function testIsNotValidWhenNullOrEmptyEvenWithOtherValidatorsSet() {
    $field = new Field('favnum');
    $field->addValidator($this->alwaysInvalidValidator());

    $this->assertIsNotValid($field, null);
    $this->assertIsNotValid($field, '');
    $this->assertIsNotValid($field, 30);
  }

  public function testWithValidators() {
    $field = Field::withValidators(
        'age',
        $this->alwaysValidValidator(),
        $this->alwaysInvalidValidator(),
        $this->alwaysInvalidValidator()
    );
    $this->assertIsNotValid($field, 'foo', 2);
    $this->assertIsNotValid($field, null, 2);
  }

  public function testInvalidOptionNamesFail() {
    $this->assertInvalidFieldName(null);
    $this->assertInvalidFieldName(true);
    $this->assertInvalidFieldName('');
    $this->assertInvalidFieldName(['foo' =>'bar']);
    $this->assertInvalidFieldName((object) ['foo' =>'bar']);
  }

  private function assertInvalidFieldName($name) {
    $this->assertExceptionWillThrow(
        ConfigException::class,
        function() use ($name) { new Field($name); }
    );
  }
}