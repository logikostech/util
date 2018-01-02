<?php

namespace Logikos\Util\Config\Option\Validator;

use Logikos\Util\Config\Option\Validator;

class NotNullOrEmptyString implements Validator {

  public function validate($value) {
    return !is_null($value) && $value !== '';
  }

  public function getMessage() {
    return 'Value can not be null or empty string';
  }
}