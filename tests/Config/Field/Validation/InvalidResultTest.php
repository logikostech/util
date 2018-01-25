<?php

namespace Logikos\Util\Tests\Config\Field\Validation;

use Logikos\Util\Config\Field\Validation\InvalidResult;
use Logikos\Util\Config\Field\ValidationResult;
use Logikos\Util\Tests\Config\TestCase;

class InvalidResultTest extends TestCase {

  public function testImplementsValidationResult() {
    $this->assertInstanceOf(ValidationResult::class, new InvalidResult(['foo']));
  }

  public function testIsNotValid() {
    $this->assertFalse((new InvalidResult(['foo']))->isValid());
  }

  public function testConstructWithMessages() {
    $reasons = [
        'To much foo',
        'Not enough bar'
    ];
    $sut = new InvalidResult($reasons);
    $this->assertEquals($reasons, $sut->getMessages());
  }
}