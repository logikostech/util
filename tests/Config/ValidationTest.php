<?php


namespace LogikosTest\Util\Config;

use Logikos\Util\Config\MutableConfig as Config;
use Logikos\Util\Config\Validation as ConfigValidation;
use Logikos\Util\Validation;
use Logikos\Util\Validation\Validator;
use LogikosTest\Util\ValidationTestingTrait;

class ValidationTest extends TestCase {
  use ValidationTestingTrait;

  /** @var  ConfigValidation */
  protected $validation;

  public function setUp() {
    $this->validation = new ConfigValidation;
  }

  public function testWhenNoValidatorsSet_ThenIsValid() {
    $conf = new Config(['foo'=>'bar']);
    $this->assertConfigIsValid($conf);
  }

  public function testAddFieldValidation() {
    $conf = new Config();
    $this->validation->addFieldValidators(
        'age',
        new Validator\Callback('is_int', 'Must be an integer')
    );
    $conf->age = 'ten';
    $this->assertConfigIsNotValid($conf);;
    $conf->age = 50;
    $this->assertConfigIsValid($conf);
  }

  private function assertConfigIsValid(Config $conf) {
    $this->assertTrue(
        $this->validation->validate($conf)->isValid(),
        "Expected config to be valid but it was invalid"
    );
  }

  private function assertConfigIsNotValid(Config $conf) {
    $this->assertFalse(
        $this->validation->validate($conf)->isValid(),
        "Expected config to be invalid but it was valid"
    );
  }

}