<?php

namespace Logikos\Util\Validation;


class ValidResult implements Result {

  public function isValid(): bool {
    return self::VALID;
  }

  public function getMessages() {
    return [];
  }
}