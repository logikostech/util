<?php

namespace Logikos\Util\Tests\Config\Option;

use Logikos\Util\Config\Option\RequiredOption;

class RequiredOptionTest extends TestCase {
  public function testSetAndGetOptionName() {
    $name = 'first-name';
    $o = new RequiredOption($name);
    $this->assertSame($name, $o->getName());
  }

  public function testIsRequired() {
    $o = new RequiredOption('foo');
    $this->assertTrue($o->isRequired());
  }

  public function testNotRequiredWithValidatorIsValidIfNull() {
    $o = RequiredOption::withValidators('age', $this->intValidator());
    $this->assertFalse($o->isValidValue(null));
    $this->assertSame(1, count($o->validationMessages(null)));
  }

  public function testValidationMessageWhenValueIsNull() {
    $o = new RequiredOption('foo');
    $this->assertCount(1, $o->validationMessages());
  }
}