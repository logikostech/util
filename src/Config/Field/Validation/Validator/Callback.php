<?php

namespace Logikos\Util\Config\Field\Validation\Validator;


use Logikos\Util\Config\Field\Validation\Validator;

class Callback implements Validator {
  /**
   * @var callable
   */
  private $callable;
  private $description;

  public function __construct(callable $callable, $description) {
    $this->callable    = $callable;
    $this->description = $description;
  }

  public function getDescription() {
    return $this->description;
  }

  public function validate($value) : bool {
    return call_user_func($this->callable, $value);
  }
}