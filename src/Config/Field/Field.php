<?php

namespace Logikos\Util\Config\Field;

use Logikos\Util\Config\Field as FieldInterface;
use Logikos\Util\Validation;
use Logikos\Util\Validation\Validator;
use Logikos\Util\Validation\Result;

class Field implements FieldInterface {

  protected $name;

  /** @var Validation */
  protected $validation;

  public function __construct($name) {
    if (!$this->isValidName($name))
      throw new InvalidFieldNameException();
    $this->name = $name;

    $this->validation = new Validation();
  }

  public static function withValidators($name, Validator ...$validators) {
    $field = new static($name);
    $field->validation->addValidator(...$validators);
    return $field;
  }

  public function isRequired() : bool {
    return true;
  }

  public function getName() {
    return $this->name;
  }

  public function validate($value): Result {
    return $this->isRequired()
        ? $this->validateRequired($value)
        : $this->validateOptional($value);
  }

  protected function validateRequired($value): Result {
    $result = $this->validationResult($value);

    return $result->isValid() && $this->isEmpty($value)
        ? $this->invalidResult(['Required'])
        : $result;
  }

  protected function validateOptional($value): Result {
    return $this->isEmpty($value)
        ? new Validation\ValidResult()
        : $this->validationResult($value);
  }

  protected function validationResult($value): Result {
    return $this->validation->validate($value);
  }

  protected function invalidResult($messages) {
    return new Validation\InvalidResult($messages);
  }

  public function addValidator(Validator ...$validator) {
    $this->validation->addValidator(...$validator);
  }

  public function addPattern($pattern, $description) {
    $this->addValidator(new Validator\Regex($pattern, $description));
  }

  public function addCallable(callable $callable, $description) {
    $this->addValidator(new Validator\Callback($callable, $description));
  }

  protected function isEmpty($value) {
    return is_null($value) || $value === '';
  }

  private function isValidName($name) {
    return is_int($name)
        || is_string($name)
        && !empty($name);
  }
}