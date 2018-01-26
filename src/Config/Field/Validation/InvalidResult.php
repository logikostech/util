<?php

namespace Logikos\Util\Config\Field\Validation;

class InvalidResult implements Result {

  private $messages;

  public function __construct(array $messages) {
    $this->messages = $messages;
  }

  public function isValid(): bool {
    return self::INVALID;
  }

  public function getMessages() {
    return $this->messages;
  }
}