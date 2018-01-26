<?php

namespace Logikos\Util\Tests\Config\Field;


use Logikos\Util\Config\Field\OptionalField;

class OptionalFieldTest extends TestCase {
  public function testIsRequiredShouldBeFalse() {
    $this->assertFalse((new OptionalField('foo'))->isRequired());
  }

  public function testIsValidWhenNullOrEmpty() {
    $this->assertIsValid(new OptionalField('username'), null);
    $this->assertIsValid(new OptionalField('username'), '');
  }

  
}