<?php

namespace LogikosTest\Util\Config;


use Logikos\Util\Config;
use Logikos\Util\Config\InvalidConfigStateException;
use Logikos\Util\Config\Field;

class StrictConfigTest extends TestCase {
  public function testImplementsConfig() {
    $sut = new class extends Config\StrictConfig {
      protected function initialize() {}
    };
    $this->assertInstanceOf(Config::class, $sut);
  }

  public function testValid() {
    $config = $this->validConfig(['name'=>'fred', 'email'=>'foo@bar.com']);
    $config->validate(); // make sure no exception is thrown
    $this->assertTrue($config->isValid());
  }

  public function testInvalid() {
    $config = $this->validConfig(['age'=>30]);
    $this->assertFalse($config->isValid());
  }

  public function testWhenInvalidValidateThrowsException() {
    $this->expectException(InvalidConfigStateException::class);
    $config = $this->validConfig(['age'=>30]);
    $config->validate();
  }

  public function testGetValidationMessagesWhenRequiredFieldNotSent() {
    $config = $this->validConfig(['name'=>'fred', 'age'=>30]);
    $this->assertEquals(
        [
            'email' => [
                'Required'
            ]
        ],
        $config->validationMessages()
    );
  }

  public function testGetValidationMessagesWhenValidationFails() {
    $config = $this->validConfig(['name'=>'a!', 'email'=>'foo@bar.com']);
    $this->assertEquals(
        [
            'name' => [
                'Letters only',
                'Length must be between 3 and 20'
            ]
        ],
        $config->validationMessages()
    );
  }

  protected function validConfig($config=[]) {
    return new class($config) extends Config\StrictConfig {
      protected function initialize() {
        $this->addFields(
            $this->nameField(),
            new Config\Field\OptionalField('age'),
            $this->favNumField(),
            new Field\Field('email')
        );
      }

      private function nameField() {
        $field = new Field\Field('name');
        $field->addPattern('/^[a-zA-Z]+$/', 'Letters only');
        $field->addPattern('/^.{3,20}$/', 'Length must be between 3 and 20');
        return $field;
      }

      private function favNumField() {
        $field = new Field\OptionalField('favnum');
        $field->addCallable(
            function($value){ return is_int($value) && $value >=0 && $value <= 100; },
            'Must be between 0 and 100'
        );
        return $field;
      }
    };
  }
}