<?php


namespace Logikos\Util;


use Logikos\Util\Validation\Result;
use Logikos\Util\Validation\Validator;

class Validation {

  /** @var Validation\Validator[] */
  protected $validators = [];

  public function addValidator(Validator ...$validators) {
    foreach($validators as $v)
      array_push($this->validators, $v);
  }

  public function validate($value): Result {
    $messages = [];
    foreach ($this->validators as $validator) {
      if (!$validator->validate($value))
        array_push($messages, $validator->getDescription());
    }
    return count($messages)
        ? new Validation\InvalidResult($messages)
        : new Validation\ValidResult();
  }
}