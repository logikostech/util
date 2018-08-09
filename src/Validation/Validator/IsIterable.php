<?php

namespace Logikos\Util\Validation\Validator;


use Logikos\Util\Validation\Validator;

class IsIterable implements Validator {
  private $description;

  public function __construct($description = null) {
    if (!is_null($description))
      $this->description = $description;
  }

  public function validate($value): bool {
    if (is_array($value)) return true;
    if (is_object($value) && $value instanceof \Traversable) return true;
    return false;
  }

  public function getDescription() {
    return $this->description;
  }
}