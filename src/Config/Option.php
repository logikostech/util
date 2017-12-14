<?php

namespace Logikos\Util\Config;

use Logikos\Util\Config\Option\InvalidOptionNameException;
use Logikos\Util\Config\Option\Validator;

class Option {
  private $name;

  /** @var Validator[] */
  private $validators;

  /**
   * @param  string $name
   * @throws InvalidOptionNameException
   */
  public function __construct($name = null) {
    if (!$this->isValidName($name))
      throw new InvalidOptionNameException();

    $this->name = $name;
  }

  public static function withValidators($name, Validator ...$validators) {
    $self = new static($name);
    $self->validators = $validators;
    return $self;
  }

  public function getName() {
    return $this->name;
  }

  private function isValidName($name) {
    return is_int($name)
        || is_string($name)
        && !empty($name);
  }

  public function isValidValue($value) {
    $isValid = true;

    foreach ($this->validators as $validator)
      if (!$validator->validate($value))
        $isValid = false;

    return $isValid;
  }
}