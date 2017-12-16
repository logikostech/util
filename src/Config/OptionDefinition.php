<?php

namespace Logikos\Util\Config;

use Logikos\Util\Config\Option\InvalidOptionNameException;
use Logikos\Util\Config\Option\Validator;

class OptionDefinition implements Option {
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

  public function validationMessages($value) {
    $messages = [];

    foreach ($this->validators as $validator)
      if (!$validator->validate($value))
        array_push($messages, $validator->getMessage());

    return $messages;
  }

  public function isValidValue($value) {
    foreach ($this->validators as $validator)
      if (!$validator->validate($value))
        return false;
    return true;
  }

  private function isValidName($name) {
    return is_int($name)
        || is_string($name)
        && !empty($name);
  }
}