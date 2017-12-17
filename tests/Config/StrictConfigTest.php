<?php

namespace Logikos\Util\Tests\Config;

use Logikos\Util\Config\Option;
use Logikos\Util\Config\OptionDefinition;
use Logikos\Util\Config\OptionNotDefinedException;
use Logikos\Util\Config\StrictConfig;

class StrictConfigTest extends TestCase {

  public function testCanDefineSettableOption() {
    $sut = new class extends StrictConfig {
      public function onConstruct() {
        $this->addOption(new OptionDefinition('name'));
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

    $this->assertSame(false, $invalidConfig->isValid());
    $this->assertSame(true,  $validConfig->isValid());
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



  private function alwaysValidOption($name='foo') {
    return new class($name) implements Option {
      private $name;
      public function __construct($name)     { $this->name = $name; }
      public function getName()              { return $this->name;  }
      public function validationMessages($v) { return [];           }
      public function isValidValue($v)       { return true;         }
    };
  }

  private function alwaysInvalidOption($name='foo', $reasons=['reason']) {
    return new class($name, $reasons) implements Option {
      private $name, $reasons;
      public function __construct($name, $reason) {
        $this->name = $name; $this->reasons = $reason;
      }
      public function getName()              { return $this->name;    }
      public function validationMessages($v) { return $this->reasons; }
      public function isValidValue($v)       { return false;          }
    };
  }
}