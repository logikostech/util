<?php

namespace LogikosTest\Util\Validation;

use Logikos\Util\Validation\ValidResult;
use Logikos\Util\Validation\Result as ValidationResult;
use LogikosTest\Util\Config\TestCase;

class ValidResultTest extends TestCase {

  public function testImplementsValidationResult() {
    $this->assertInstanceOf(ValidationResult::class, new ValidResult());
  }

  public function testIsValid() {
    $sut = new ValidResult();
    $this->assertTrue($sut->isValid());
  }

  public function testMessagesIsEmptyArray() {
    $this->assertEquals([], (new ValidResult)->getMessages());
  }
}