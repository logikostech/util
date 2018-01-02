<?php

namespace Logikos\Util\Tests\Config\Option;

use Logikos\Util\Config\Option\InvalidOptionNameException;
use Logikos\Util\Config\Option\NonRequiredOption;

class NonRequiredOptionTest extends TestCase {
  public function testSetAndGetOptionName() {
    $name = 'first-name';
    $o = new NonRequiredOption($name);
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
    $this->assertValidOptionName('string');
    $this->assertValidOptionName(123);
  }

  public function testWithValidator() {
    $o = NonRequiredOption::withValidators('age', $this->intValidator());
    $this->assertFalse($o->isValidValue('string'));
    $this->assertTrue($o->isValidValue(1));
  }

  public function testMultipleValidators() {
    $o = NonRequiredOption::withValidators(
        'password',
        $this->lengthValidator(5),
        $this->stringValidator()
    );
    $this->assertTrue($o->isValidValue('some string longer than 5 chars'));
    $this->assertFalse($o->isValidValue('str'));
    $this->assertFalse($o->isValidValue(123456789));
  }

  public function testIsRequired() {
    $o = new NonRequiredOption('foo');
    $this->assertFalse($o->isRequired());
  }

  public function testNotRequiredWithValidatorIsValidIfNull() {
    $o = NonRequiredOption::withValidators('age', $this->intValidator());
    $this->assertTrue($o->isValidValue(null));
    $this->assertSame(0, count($o->validationMessages(null)));
  }

  public function testValidationMessages() {
    $lengthValidator = $this->lengthValidator(5);
    $stringValidator = $this->stringValidator();

    $o = NonRequiredOption::withValidators(
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

    $this->assertEquals(
        [
            $lengthValidator->getMessage(),
            $stringValidator->getMessage()
        ],
        $o->validationMessages(123)
    );
  }

  protected function assertInvalidOptionName($name) {
    $this->assertExceptionWillThrow(
        InvalidOptionNameException::class,
        function() use ($name) { new NonRequiredOption($name); }
    );
  }

  protected function assertValidOptionName($name) {
    // we just need to make sure an exception is not thrown...
    $this->assertInstanceOf(NonRequiredOption::class, new NonRequiredOption($name));
  }
}