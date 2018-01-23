<?php

namespace Logikos\Util\Tests\Config;

use Logikos\Util\Config\Option;
use Logikos\Util\Config\Option\NonRequiredOption;
use Logikos\Util\Config\OptionNotDefinedException;
use Logikos\Util\Config\StrictConfig;
use Logikos\Util\Tests\Config\Option\AlwaysInvalidOption;
use Logikos\Util\Tests\Config\Option\AlwaysValidOption;

class StrictConfigTest extends TestCase {

  public function testCanDefineSettableOption() {
    $sut = new class extends StrictConfig {
      public function onConstruct() {
        $this->addOption(new NonRequiredOption('name'));
      }
    };

    $sut['name'] = 'fred';
    $this->assertEquals('fred', $sut['name']);
  }

  public function testTryingToSetUndefinedOptionThrowsException() {
    $sut = new class extends StrictConfig {};
    $this->expectException(OptionNotDefinedException::class);
    $sut['age'] = 50;
  }

  public function testSetValueRunsOptionValidation() {
    $sut = $this->strictConfigWithOptions(
        $this->alwaysInvalidOption('age')
    );

    $this->expectException(Option\InvalidOptionValueException::class);
    $sut['age'] = 'string';
  }

  public function testIsValid() {
    $validConfig   = $this->strictConfigWithOptions($this->alwaysValidOption());
    $invalidConfig = $this->strictConfigWithOptions($this->alwaysInvalidOption());

    $this->assertConfigValid($validConfig);
    $this->assertConfigNotValid($invalidConfig);
  }

  public function testIsValidWithOptionSet() {
    $sut = $this->strictConfigWithOptions($this->alwaysValidOption('age'));
    $sut['age'] = 40;
    $this->assertConfigValid($sut);
  }

  public function testCanGetInvalidOptionMessages() {
    $sut = $this->strictConfigWithOptions(
        $this->alwaysInvalidOption('a', ['Foo', 'Bar']),
        $this->alwaysValidOption('b'),
        $this->alwaysInvalidOption('c', ['Baz'])
    );

    $this->assertEquals(
        [
            'a' => ['Foo', 'Bar'],
            'c' => ['Baz']
        ],
        $sut->validationMessages()
    );
  }

  public function testGetOptionKeys() {
    $sut = $this->strictConfigWithOptions(
        $this->alwaysValidOption('foo'),
        $this->alwaysValidOption('bar')
    );
    $this->assertSame(
        ['foo', 'bar'],
        $sut->getOptionKeys()
    );
  }

  public function testAddOptions() {
    $sut = new class extends StrictConfig {
      protected function onConstruct() {
        $this->addOptions(
            new NonRequiredOption('name'),
            new NonRequiredOption('age')
        );
      }
    };
    $this->assertSame(
        ['name', 'age'],
        $sut->getOptionKeys()
    );
  }

  private function assertConfigValid(StrictConfig $config) {
    $this->assertTrue($config->isValid());
  }

  private function assertConfigNotValid(StrictConfig $config) {
    $this->assertFalse($config->isValid());
  }

  private function strictConfigWithOptions(Option ...$optionList) : StrictConfig {
    $sut = new class extends StrictConfig {
      public static function withOptions(Option ...$options) {
        $self = new static;
        foreach ($options as $option) $self->addOption($option);
        return $self;
      }
    };
    return $sut::withOptions(...$optionList);
  }



  private function alwaysValidOption($name='alwaysValid') {
    return new AlwaysValidOption($name);
  }

  private function alwaysInvalidOption($name='alwaysInvalid', $reasons=['reason']) {
    return new AlwaysInvalidOption($name, $reasons);
  }
}