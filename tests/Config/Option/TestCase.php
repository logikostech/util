<?php

namespace Logikos\Util\Tests\Config\Option;

use Logikos\Util\Config\Option\Validator;

class TestCase extends \Logikos\Util\Tests\Config\TestCase {

  protected function intValidator() : Validator {
    return new class implements Validator {
      public function validate($value) { return is_int($value);       }
      public function getMessage()     { return 'Must be an integer'; }
    };
  }

  protected function stringValidator() : Validator {
    return new class implements Validator {
      public function validate($value) { return is_string($value);    }
      public function getMessage()     { return 'Must be an integer'; }
    };
  }

  protected function lengthValidator($minLength=3) : Validator {
    return new class($minLength) implements Validator {
      private $min;
      public function __construct($minLength) { $this->min = $minLength; }
      public function validate($value) { return strlen($value) > $this->min; }
      public function getMessage()     { return "Must be longer than {$this->min} chars";  }
    };
  }
}