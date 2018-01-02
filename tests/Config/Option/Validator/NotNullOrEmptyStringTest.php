<?php

namespace Logikos\Util\Tests\Config\Option\Validator;


use Logikos\Util\Config\Option\Validator\NotNullOrEmptyString;

class NotNullOrEmptyStringTest extends TestCase {

  public function testInvalidValues() {
    $sut = new NotNullOrEmptyString();
    $this->assertInvalid($sut, null);
    $this->assertInvalid($sut, '');
  }

  public function testValidValues() {
    $sut = new NotNullOrEmptyString();
    $this->assertIsValid($sut, 0);
    $this->assertIsValid($sut, ' ');
    $this->assertIsValid($sut, "abc ABC 123 !@#$%^&*()_+-=[]{}\\|;:,.<>/?\n\r\t");
  }

  public function testCanGetMessage() {
    $sut = new NotNullOrEmptyString();
    $this->assertTrue(strlen($sut->getMessage()) > 1);
  }
}