<?php


namespace LogikosTest\Util;

use Logikos\Util\Validation;
use Logikos\Util\Validation\Validator;

Trait ValidationTestingTrait {


  protected function alwaysInvalidValidator($description = 'invalid') {
    return new class($description) implements Validator {
      private $desc;
      public function __construct($desc)    { $this->desc = $desc; }
      public function validate($value):bool { return false;        }
      public function getDescription()      { return $this->desc;  }
    };
  }

  protected function alwaysValidValidator() {
    return new class implements Validator {
      public function validate($value):bool { return true;        }
      public function getDescription()      { return 'your good'; }
    };
  }

  protected function assertValidationPasses(Validation $validation, $value) {
    $this->assertTrue($validation->validate($value)->isValid());
  }

  protected function assertValidationFails(Validation $validation, $value, $msgCount=null) {
    $result = $validation->validate($value);
    $this->assertFalse($result->isValid());
    if (!is_null($msgCount))
      $this->assertEquals($msgCount, count($result->getMessages()));
  }
}