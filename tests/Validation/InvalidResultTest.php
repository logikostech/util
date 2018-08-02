<?php

namespace Logikos\Util\Tests\Config\Field\Validation;

use Logikos\Util\Validation;
use Logikos\Util\Tests\Config\TestCase;

class InvalidResultTest extends TestCase {

  public function testImplementsValidationResult() {
    $this->assertInstanceOf(Validation\Result::class, new Validation\InvalidResult(['foo']));
  }

  public function testIsNotValid() {
    $this->assertFalse((new Validation\InvalidResult(['foo']))->isValid());
  }

  public function testConstructWithMessages() {
    $reasons = [
        'To much foo',
        'Not enough bar'
    ];
    $sut = new Validation\InvalidResult($reasons);
    $this->assertEquals($reasons, $sut->getMessages());
  }
}