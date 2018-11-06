<?php

namespace LogikosTest\Util\Config\Field;


use Logikos\Util\Config\Field\OptionalField;

class OptionalFieldTest extends TestCase {
  public function testIsRequiredShouldBeFalse() {
    $this->assertFalse((new OptionalField('foo'))->isRequired());
  }

  public function testIsValidWhenNullOrEmpty() {
    $this->assertIsValid(new OptionalField('username'), null);
    $this->assertIsValid(new OptionalField('username'), '');
  }

  public function testIsValidWhenNullOrEmptyEvenWithOtherValidatorsSet() {
    $field = new OptionalField('favnum');
    $field->addValidator($this->alwaysInvalidValidator());

    $this->assertIsValid($field, null);
    $this->assertIsValid($field, '');
    $this->assertIsNotValid($field, 30);
  }

  public function testWithMultipleValidators() {
    $field = OptionalField::withValidators(
        'age',
        $this->alwaysValidValidator(),
        $this->alwaysInvalidValidator(),
        $this->alwaysInvalidValidator()
    );
    $this->assertIsNotValid($field, 'foo', 2);
  }

  public function testOptionalFieldValidWhenEmpty() {
    $field = OptionalField::withValidators('foo', $this->alwaysInvalidValidator());
    $this->assertIsValid($field, null);
    $this->assertIsValid($field, '');
  }

  public function testFieldValidWhenEmptyAfterHavingInvalidValue() {
    $field = OptionalField::withValidators('foo', $this->alwaysInvalidValidator());
    $this->assertIsNotValid($field, 'invalid value');
    $this->assertIsValid($field, null);
    $this->assertIsValid($field, '');
  }
}