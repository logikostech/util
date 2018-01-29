<?php

namespace Logikos\Util\Config\Field;

use Innmind\Immutable\Stream;
use Logikos\Util\Config\Field as FieldInterface;
use Logikos\Util\Config\Field\Validation\Result as ValidationResult;
use Logikos\Util\Config\Field\Validation\Validator;

abstract class Field implements FieldInterface {

  protected $name;
  protected $messages = [];

  /** @var Validator[] */
  protected $validators    = [];

  public function __construct($name) {
    $this->name = $name;
  }

  abstract public function isRequired() : bool;

  public function getName() {
    return $this->name;
  }

  public function addPattern($pattern, $description) {
    $this->addValidator(new Validator\Regex($pattern, $description));
  }

  public function addCallable(callable $callable, $description) {
    $this->addValidator(new Validator\Callback($callable, $description));
  }

  public function addValidator(Validator $validator) {
    array_push($this->validators, $validator);
  }

  public function validate($value): ValidationResult {
    if ($this->isRequiredAndEmpty($value))
      $this->addMessage('Required');

    if ($this->isNotEmpty($value))
      $this->runValidators($value);

    return $this->validationResult();
  }

  protected function isRequiredAndEmpty($value) {
    return $this->isRequired() && $this->isEmpty($value);
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

  /**
   * @param $value
   */
  protected function runValidators($value) {
    foreach ($this->validators as $validator) {
      if (!$validator->validate($value))
        $this->addMessage($validator->getDescription());
    }
  }
}