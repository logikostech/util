<?php

namespace Logikos\Util\Tests\Config\Field;

use Logikos\Util\Config\Field;
use Logikos\Util\Validation\Validator;
use Logikos\Util\Tests\Config\TestCase as ConfigTestCase;

class TestCase extends ConfigTestCase {


  protected function assertIsNotValid(Field $field, $value, $msgCount=1) {
    $result = $field->validate($value);
    $this->assertFalse($result->isValid());
    $this->assertEquals($msgCount, count($result->getMessages()));
  }

  protected function assertIsValid(Field $field, $value) {
    $this->assertTrue($field->validate($value)->isValid());
  }

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
}