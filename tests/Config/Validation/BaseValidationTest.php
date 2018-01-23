<?php

namespace Logikos\Util\Tests\Config\Validation;

use Logikos\Util\Config\Validation\BaseValidation as Validation;
use Logikos\Util\Tests\Config\TestCase;

class BaseValidationTest extends TestCase {
  public function testImpl() {
    $sut = new Validation();
    $this->assertInstanceOf(Validation::class, $sut);
  }

  public function testRequiredField() {
    $sut = new Validation();
    $field = 'name';
    $sut->requiredField($field);
    $this->assertTrue($sut->isRequired($field));
  }


}