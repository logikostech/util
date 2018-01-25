<?php

namespace Logikos\Util\Config\Field\Validation;

use Logikos\Util\Config\Field\ValidationResult;

class Result implements ValidationResult {

  private $isValid;
  private $messages;
  private $value;

  public function __construct($isValid, $messages, $value) {
    $this->isValid  = (bool) $isValid;
    $this->messages = $messages;
    $this->value    = $value;
  }

  public function isValid(): bool {
  }

  public function getMessages() {
  }

  public function getValue() {
  }
}