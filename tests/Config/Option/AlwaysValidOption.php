<?php

namespace Logikos\Util\Tests\Config\Option;

use Logikos\Util\Config\Option;

class AlwaysValidOption implements Option {
  private $name;
  public function __construct($name)     { $this->name = $name; }
  public function getName()              { return $this->name;  }
  public function validationMessages($v) { return [];           }
  public function isValidValue($v)       { return true;         }
  public function isRequired()           { return false;        }
}