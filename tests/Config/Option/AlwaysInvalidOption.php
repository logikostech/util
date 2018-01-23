<?php

namespace Logikos\Util\Tests\Config\Option;

use Logikos\Util\Config\Option;

class AlwaysInvalidOption implements Option {
  private $name, $reasons;
  public function __construct($name=null, $reasons=null) {
    $this->name = $name ?? uniqid('alwaysInvalid');
    $this->reasons = $reasons ?? ['reason'];
  }
  public function getName()              { return $this->name;    }
  public function validationMessages($v) { return $this->reasons; }
  public function isValidValue($v)       { return false;          }
  public function isRequired()           { return false;          }
}