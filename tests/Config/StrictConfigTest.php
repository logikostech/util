<?php

namespace Logikos\Util\Tests\Config;

use Logikos\Util\Config\Option;
use Logikos\Util\Config\Option\Validator;
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
        new OptionDefinition('foo'),
        new OptionDefinition('bar')
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
            new OptionDefinition('name'),
            new OptionDefinition('age')
        );
      }
    };
    $this->assertSame(
        ['name', 'age'],
        $sut->getOptionKeys()
    );
  }

  public function testGetOptions() {
    $sut = new class extends StrictConfig {
      protected function onConstruct() {
        $this->addOptions(
            OptionDefinition::withValidators(
                'name',
                new Validator\PatternMatch(
                    '/[a-zA-Z]/',
                    'May contain only upper and lower case letters'
                ),
                new Validator\PatternMatch(
                    '/[a-zA-Z]/',
                    'Must be between 2 and 30 characters long'
                )
            ),
            OptionDefinition::withValidators(
                'age',
                new Validator\Custom(
                    function($v){return is_int($v) && $v>0;},
                    'Must be a number greater than zero'
                )
            ),
            new OptionDefinition('fav color')
        );
      }
    };
    $expected = [
        'name' => [
            'May contain only upper and lower case letters',
            'Must be between 2 and 30 characters long'
        ],
        'age' => ['Must be a number greater than zero'],
        'fav color' => []
    ];
    $this->assertEquals($expected, $sut->AllMessages());
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
    return new class($name) implements Option {
      private $name;
      public function __construct($name)     { $this->name = $name; }
      public function getName()              { return $this->name;  }
      public function validationMessages($v) { return [];           }
      public function isValidValue($v)       { return true;         }
    };
  }

  private function alwaysInvalidOption($name='alwaysInvalid', $reasons=['reason']) {
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