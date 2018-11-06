<?php

namespace Logikos\Util\Config\Field;

use Logikos\Util\Config\Field as FieldInterface;
use Logikos\Util\Validation;

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

  public static function withValidators($name, Validation\Validator ...$validators) {
    $field = new static($name);
    $field->validation->addValidators(...$validators);
    return $field;
  }

  public function isRequired() : bool {
    return true;
  }

  public function getName() {
    return $this->name;
  }

  public function validate($value): Validation\Result {
    return $this->isRequired()
        ? $this->validateRequired($value)
        : $this->validateOptional($value);
  }

  protected function validateRequired($value): Validation\Result {
    $result = $this->validationResult($value);

    return $result->isValid() && $this->isEmpty($value)
        ? $this->invalidResult(['Required'])
        : $result;
  }

  protected function validateOptional($value): Validation\Result {
    return $this->isEmpty($value)
        ? new Validation\ValidResult()
        : $this->validationResult($value);
  }

  protected function validationResult($value): Validation\Result {
    return $this->validation->validate($value);
  }

  protected function invalidResult($messages) {
    return new Validation\InvalidResult($messages);
  }

  public function addValidator(Validation\Validator $validator) {
    $this->validation->addValidator($validator);
  }

  public function addPattern($pattern, $description) {
    $this->addValidator(new Validation\Validator\Regex($pattern, $description));
  }

  public function addCallable(callable $callable, $description) {
    $this->addValidator(new Validation\Validator\Callback($callable, $description));
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