<?php

namespace Logikos\Util\Config\Field;

class RequiredField extends Field {
  public function isRequired() : bool {
    return true;
  }
}