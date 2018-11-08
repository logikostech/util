<?php

namespace LogikosTest\Util\Config\Field;


use Logikos\Util\Config\Field\OptionalField;

class OptionalFieldTest extends TestCase {
  public function testIsRequiredShouldBeFalse() {
    $this->assertFalse((new OptionalField('foo'))->isRequired());
  }

  public function testIsValidWhenNullOrEmpty() {
    $this->assertFieldIsValid(new OptionalField('username'), null);
    $this->assertFieldIsValid(new OptionalField('username'), '');
  }

  public function testIsValidWhenNullOrEmptyEvenWithOtherValidatorsSet() {
    $field = new OptionalField('favnum');
    $field->addValidator($this->alwaysInvalidValidator());

    $this->assertFieldIsValid($field, null);
    $this->assertFieldIsValid($field, '');
    $this->assertFieldIsNotValid($field, 30);
  }

  public function testWithMultipleValidators() {
    $field = OptionalField::withValidators(
        'age',
        $this->alwaysValidValidator(),
        $this->alwaysInvalidValidator(),
        $this->alwaysInvalidValidator()
    );
    $this->assertFieldIsNotValid($field, 'foo', 2);
  }

  public function testOptionalFieldValidWhenEmpty() {
    $field = OptionalField::withValidators('foo', $this->alwaysInvalidValidator());
    $this->assertFieldIsValid($field, null);
    $this->assertFieldIsValid($field, '');
  }

  public function testFieldValidWhenEmptyAfterHavingInvalidValue() {
    $field = OptionalField::withValidators('foo', $this->alwaysInvalidValidator());
    $this->assertFieldIsNotValid($field, 'invalid value');
    $this->assertFieldIsValid($field, null);
    $this->assertFieldIsValid($field, '');
  }
}