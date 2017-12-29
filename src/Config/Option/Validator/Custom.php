<?php

namespace Logikos\Util\Config\Option\Validator;


use Logikos\Util\Config\Option\Validator;

class Custom implements Validator {
  /** @var callable */
  private $callable;

  private $message;

  public function __construct(callable $callable, $message = null) {
    $this->callable = $callable;
    $this->message = $message;
  }

  public function validate($value) {
    return call_user_func($this->callable, $value);
  }

  public function getMessage() {
    return $this->message;
  }
}