<?php

namespace Logikos\Util\Tests\Config;


use Logikos\Util\Config\Option\InvalidOptionNameException;
use Logikos\Util\Config\OptionDefinition;
use Logikos\Util\Config\Option\Validator;

class OptionDefinitionTest extends TestCase {
  public function testSetAndGetOptionName() {
    $name = 'first-name';
    $o = new OptionDefinition($name);
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
    $o = OptionDefinition::withValidators('age', $this->intValidator());
    $this->assertFalse($o->isValidValue('string'));
    $this->assertTrue($o->isValidValue(1));
  }

  public function testMultipleValidators() {
    $o = OptionDefinition::withValidators(
        'password',
        $this->lengthValidator(5),
        $this->stringValidator()
    );
    $this->assertTrue($o->isValidValue('some string longer than 5 chars'));
    $this->assertFalse($o->isValidValue('str'));
    $this->assertFalse($o->isValidValue(123456789));
  }

  public function testValidationMessages() {
    $lengthValidator = $this->lengthValidator(5);
    $stringValidator = $this->stringValidator();

    $o = OptionDefinition::withValidators(
        'password',
        $lengthValidator,
        $stringValidator
    );

    $this->assertEquals(
        [
            $lengthValidator->getMessage()
        ],
        $o->validationMessages('str')
    );

    $this->assertEquals(
        [
            $stringValidator->getMessage()
        ],
        $o->validationMessages(123456)
    );

    ;
    $this->assertEquals(
        [
            $lengthValidator->getMessage(),
            $stringValidator->getMessage()
        ],
        $o->validationMessages(123)
    );
  }

  private function assertInvalidOptionName($name) {
    $this->assertExceptionWillThrow(
        InvalidOptionNameException::class,
        function() use ($name) { new OptionDefinition($name); }
    );
  }

  private function assertIsValidOptionName($name) {
    // we just need to make sure an exception is not thrown...
    $this->assertInstanceOf(OptionDefinition::class, new OptionDefinition($name));
  }

  private function intValidator() : Validator {
    return new class implements Validator {
      public function validate($value) { return is_int($value);       }
      public function getMessage()     { return 'Must be an integer'; }
    };
  }

  private function stringValidator() : Validator {
    return new class implements Validator {
      public function validate($value) { return is_string($value);    }
      public function getMessage()     { return 'Must be an integer'; }
    };
  }

  private function lengthValidator($minLength=3) : Validator {
    return new class($minLength) implements Validator {
      private $min;
      public function __construct($minLength) { $this->min = $minLength; }
      public function validate($value) { return strlen($value) > $this->min; }
      public function getMessage()     { return "Must be longer than {$this->min} chars";  }
    };
  }
}