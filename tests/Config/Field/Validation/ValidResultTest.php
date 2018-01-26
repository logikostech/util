<?php

namespace Logikos\Util\Tests\Config\Field\Validation;

use Logikos\Util\Config\Field\Validation\ValidResult;
use Logikos\Util\Config\Field\Validation\Result as ValidationResult;
use Logikos\Util\Tests\Config\TestCase;

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