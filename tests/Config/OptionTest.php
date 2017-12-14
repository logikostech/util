<?php

namespace Logikos\Util\Tests\Config;


use Logikos\Util\Config\Option;
use Logikos\Util\Config\Option\Validator\Set;

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

  public function testWithValidator() {
    $o = Option::withValidators('age', $this->intValidator());
    $this->assertFalse($o->isValidValue('string'));
    $this->assertTrue($o->isValidValue(1));
  }

  public function testMultipelValidators() {
    $o = Option::withValidators(
        'password',
        $this->lengthValidator(5),
        $this->stringValidator()
    );
    $this->assertTrue($o->isValidValue('some string longer than 5 chars'));
    $this->assertFalse($o->isValidValue('str'));
    $this->assertFalse($o->isValidValue(123456789));
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

  private function intValidator() : Option\Validator {
    return new class implements Option\Validator {
      public function validate($value) { return is_int($value);       }
      public function getMessage()     { return 'Must be an integer'; }
    };
  }

  private function stringValidator() : Option\Validator {
    return new class implements Option\Validator {
      public function validate($value) { return is_string($value);    }
      public function getMessage()     { return 'Must be an integer'; }
    };
  }

  private function lengthValidator($minLength=3) : Option\Validator {
    return new class($minLength) implements Option\Validator {
      private $min;
      public function __construct($minLength) { $this->min = $minLength; }
      public function validate($value) { return strlen($value) > $this->min; }
      public function getMessage()     { return "Must be longer than {$this->min} chars";  }
    };
  }
}