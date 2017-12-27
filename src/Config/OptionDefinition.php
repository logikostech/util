<?php

namespace Logikos\Util\Config;

use Logikos\Util\Config\Option\InvalidOptionNameException;
use Logikos\Util\Config\Option\Validator;

class OptionDefinition implements Option {
  private $name;

  /** @var Validator[] */
  private $validators = [];
  private $required   = false;

  /**
   * @param  string $name
   * @throws InvalidOptionNameException
   */
  public function __construct($name = null) {
    if (!$this->isValidName($name))
      throw new InvalidOptionNameException();

    $this->name = $name;
  }

  public function getName()      {
    return $this->name;
  }

  public function makeRequired($message = null) {
    $this->required = $message ?? 'Field required';
  }

  public function notRequired() { $this->required = false;           }
  public function isRequired()  { return $this->required !== false;  }

  public static function withValidators($name, Validator ...$validators) {
    $self = new static($name);
    $self->validators = $validators;
    return $self;
  }

  public function addValidators(Validator ...$validators) {
    foreach ($validators as $validator)
      array_push($this->validators, $validator);
  }

  public function validationMessages($value = null) {
    if (is_null($value) && !$this->isRequired()) return [];

    $messages = [];

    foreach ($this->validators as $validator)
      if (!$validator->validate($value))
        array_push($messages, $validator->getMessage());

    if (count($messages) === 0 && $this->isRequired() && is_null($value)) {
      array_push($messages, $this->required);
    }

    return $messages;
  }

  public function isValidValue($value) {
    if (is_null($value))
      return $this->isRequired() === false;

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