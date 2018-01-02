<?php

namespace Logikos\Util\Tests\Config;

use Logikos\Util\Config\InvalidConfigStateException;
use Logikos\Util\Config\Option;
use Logikos\Util\Config\StrictConfig;

class InvalidConfigStateExceptionTest extends TestCase {
  public function testExtendsException() {
    $config = new class extends StrictConfig {};
    $e = new InvalidConfigStateException($config);
    $this->assertInstanceOf(\Exception::class, $e);
    $this->assertSame($config, $e->getConfig());
  }

  public function testGetMessagesAsString() {
    $expected = "username:\n- required\npassword:\n- must be longer\n- must be complex";
    $e = new InvalidConfigStateException($this->fakeStrictConfig());
    $this->assertEquals($expected, $e->getMessagesAsYmlString());
  }

  public function testCanGetMessages() {
    $expected = [
        "username" => ['required'],
        "password" => ['must be longer', 'must be complex']
    ];
    $e = new InvalidConfigStateException($this->fakeStrictConfig());
    $this->assertEquals($expected, $e->getValidationMessages());
  }

  private function fakeStrictConfig() {
    return new class extends StrictConfig {
      public function onConstruct() {
        parent::onConstruct();
        $this->addOptions(
            $this->alwaysInvalidOption(
                'username',
                [
                    'required'
                ]
            ),
            $this->alwaysInvalidOption(
                'password',
                [
                    'must be longer',
                    'must be complex'
                ]
            )
        );
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
          public function isRequired()           { return false;        }
        };
      }
    };
  }
}