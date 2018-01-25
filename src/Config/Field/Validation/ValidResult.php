<?php

namespace Logikos\Util\Config\Field\Validation;


use Logikos\Util\Config\Field\ValidationResult;

class ValidResult implements ValidationResult {

  public function isValid(): bool {
    return self::VALID;
  }

  public function getMessages() {
    return [];
  }
}