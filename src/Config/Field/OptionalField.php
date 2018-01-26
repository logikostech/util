<?php

namespace Logikos\Util\Config\Field;

class OptionalField extends Field {
  public function isRequired() : bool {
    return false;
  }
}