<?php


namespace Logikos\Util;


use Logikos\Util\Validation\Validator;

class Validation {

  protected $messages = [];

  /** @var Validation\Validator[] */
  protected $validators = [];

  public function addValidator(Validation\Validator $validator) {
    array_push($this->validators, $validator);
  }

  public function addValidators(Validator ...$validators) {
    foreach($validators as $v) $this->addValidator($v);
  }

  public function validate($value) {
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