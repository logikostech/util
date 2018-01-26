<?php

namespace Logikos\Util\Config\Field\Validation;


class ValidResult implements Result {

  public function isValid(): bool {
    return self::VALID;
  }

  public function getMessages() {
    return [];
  }
}