<?php


namespace Logikos\Util\Config;

use Logikos\Util\Config;
use Logikos\Util\Validation\Result;
use Logikos\Util\Validation\Validator;
use Logikos\Util\Validation\ValidResult;

class Validation {
  public function validate(Config $config): Result {
    return new ValidResult();
  }
  public function addFieldValidators($fieldName, Validator ...$validators) {

  }
}