<?php

namespace Logikos\Util\Config\Validation;

use Logikos\Util\Config\Validation;

class BaseValidation implements Validation {
  private $requiredFields = [];

  public function requiredField($field) {
    array_push($this->requiredFields, $field);
  }

  public function isRequired($field) {
    return in_array($field, $this->requiredFields);
  }
}