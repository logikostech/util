<?php

namespace Logikos\Util\Config;

use Logikos\Util\Config\Option\InvalidOptionNameException;

class Option {
  private $name;

  public function __construct($name = null) {
    if (!$this->isValidName($name))
      throw new InvalidOptionNameException();
    $this->name = $name;
  }

  public function getName() {
    return $this->name;
  }

  private function isValidName($name) {
    if (empty($name)) return false;
    return true;
  }
}