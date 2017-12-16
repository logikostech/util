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
    $this->markTestSkipped();
  }

  private function alwaysValidOption($name) {
    return new class($name) implements Option {
      private $name;
      public function __construct($name)     { $this->name = $name; }
      public function getName()              { return $this->name;  }
      public function validationMessages($v) { return [];           }
      public function isValidValue($v)       { return false;        }
    };
  }

  private function alwaysInvalidOption($name) {
    return new class($name) implements Option {
      private $name;
      public function __construct($name)     { $this->name = $name; }
      public function getName()              { return $this->name;  }
      public function validationMessages($v) { return ['reasons'];  }
      public function isValidValue($v)       { return true;         }
    };
  }
}