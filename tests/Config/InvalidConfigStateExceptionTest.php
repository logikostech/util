<?php

namespace LogikosTest\Util\Config;

use Logikos\Util\Config\Field;
use Logikos\Util\Config\InvalidConfigStateException;
use Logikos\Util\Config\StrictConfig;
use Logikos\Util\Validation;

class InvalidConfigStateExceptionTest extends TestCase {
  public function testExtendsException() {
    $config = new class extends StrictConfig {
      protected function initialize() {}
    };
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
      public function initialize() {
        $this->addFields(
            $this->alwaysInvalidField(
                'username',
                [
                    'required'
                ]
            ),
            $this->alwaysInvalidField(
                'password',
                [
                    'must be longer',
                    'must be complex'
                ]
            )
        );
      }

      private function alwaysInvalidField($name='alwaysInvalid', $reasons=['reason']) {
        return new class($name, $reasons) implements Field {
          private $name, $reasons;
          public function __construct($name, $reason) {
            $this->name = $name; $this->reasons = $reason;
          }
          public function getName()              { return $this->name;    }

          public function validate($value): Validation\Result {
            return new Validation\InvalidResult($this->reasons);
          }
        };
      }
    };
  }
}