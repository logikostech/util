<?php

namespace Logikos\Util\Config\Field;

use Logikos\Util\Config\Field as FieldInterface;
use Logikos\Util\Validation;

class Field implements FieldInterface {

  protected $name;
  protected $messages   = [];

  /** @var Validation\Validator[] */
  protected $validators = [];

  public function __construct($name) {
    if (!$this->isValidName($name))
      throw new InvalidFieldNameException();
    $this->name = $name;
  }

  public static function withValidators($name, Validation\Validator ...$validators) {
    $field = new static($name);
    $field->validators = $validators;
    return $field;
  }

  public function isRequired() : bool {
    return true;
  }

  public function getName() {
    return $this->name;
  }

  public function addPattern($pattern, $description) {
    $this->addValidator(new Validation\Validator\Regex($pattern, $description));
  }

  public function addCallable(callable $callable, $description) {
    $this->addValidator(new Validation\Validator\Callback($callable, $description));
  }

  public function addValidator(Validation\Validator $validator) {
    array_push($this->validators, $validator);
  }

  public function validate($value): Validation\Result {
    if ($this->isRequiredOrNotEmpty($value))
      $this->runValidators($value);

    if ($this->isRequiredAndEmpty($value) && count($this->messages) === 0)
      $this->addMessage('Required');

    return $this->validationResult();
  }

  protected function isRequiredAndEmpty($value) {
    return $this->isRequired() && $this->isEmpty($value);
  }

  protected function isRequiredOrNotEmpty($value) {
    return $this->isRequired() || $this->isNotEmpty($value);
  }

  protected function addMessage($message) {
    array_push($this->messages, $message);
  }

  protected function validationResult() {
    $result = count($this->messages)
        ? new Validation\InvalidResult($this->messages)
        : new Validation\ValidResult();
    $this->resetMessages();
    return $result;
  }

  protected function resetMessages() {
    $this->messages = [];
  }

  protected function isEmpty($value) {
    return is_null($value) || $value === '';
  }

  protected function isNotEmpty($value) {
    return !$this->isEmpty($value);
  }

  protected function runValidators($value) {
    foreach ($this->validators as $validator) {
      if (!$validator->validate($value))
        $this->addMessage($validator->getDescription());
    }
  }

  private function isValidName($name) {
    return is_int($name)
        || is_string($name)
        && !empty($name);
  }
}