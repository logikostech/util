<?php

namespace Logikos\Util\Tests\Config;


use Logikos\Util\Config\Option;

class OptionTest extends TestCase {
  public function testSetAndGetOptionName() {
    $name = 'first-name';
    $o = new Option($name);
    $this->assertSame($name, $o->getName());
  }

  public function testNoNameThrowsException() {
    $this->expectException(Option\InvalidOptionNameException::class);
    new Option();
  }

  public function testNullNameThrowsException() {
    $this->expectException(Option\InvalidOptionNameException::class);
    new Option(null);
  }

  public function testEmptyNameThrowsException() {
    $this->expectException(Option\InvalidOptionNameException::class);
    new Option('');
  }
}