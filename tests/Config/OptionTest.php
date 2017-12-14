<?php

namespace Logikos\Util\Tests\Config;


use Logikos\Util\Config\Option;

class OptionTest extends TestCase {
  public function testSetAndGetOptionName() {
    $name = 'first-name';
    $o = new Option($name);
    $this->assertSame($name, $o->getName());
  }

  public function testInvalidOptionNamesFail() {
    $this->assertInvalidOptionName(null);
    $this->assertInvalidOptionName(true);
    $this->assertInvalidOptionName('');
    $this->assertInvalidOptionName(['foo'=>'bar']);
    $this->assertInvalidOptionName((object) ['foo'=>'bar']);
  }

  public function testValidOptionNamesPass() {
    $this->assertIsValidOptionName('string');
    $this->assertIsValidOptionName(123);
  }

  private function assertInvalidOptionName($name) {
    $this->assertExceptionWillThrow(
        Option\InvalidOptionNameException::class,
        function() use ($name) { new Option($name); }
    );
  }

  private function assertIsValidOptionName($name) {
    // we just need to make sure an exception is not thrown...
    $this->assertInstanceOf(Option::class, new Option($name));
  }

}