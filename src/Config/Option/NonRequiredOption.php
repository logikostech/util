<?php

namespace Logikos\Util\Config\Option;

use Logikos\Util\Config\OptionDefinition;

class NonRequiredOption extends OptionDefinition {
  public function isRequired()  { return false; }

  public function validationMessages($value = null) {
    if (is_null($value)) return [];
    return parent::validationMessages($value);
  }

  public function isValidValue($value) {
    if (is_null($value)) return true; // this option is not required, so no value is valid.
    return parent::isValidValue($value);
  }
}