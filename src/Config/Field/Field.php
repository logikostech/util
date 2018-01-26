<?php

namespace Logikos\Util\Config\Field;

use Logikos\Util\Config\Field as FieldInterface;

abstract class Field implements FieldInterface {

  protected $name;
  protected $messages = [];
  protected $regex    = [];

  public function __construct($name) {
    $this->name = $name;
  }

  abstract public function isRequired() : bool;

  public function getName() {
    return $this->name;
  }

  public function addPattern($pattern, $description) {
    $this->regex[$pattern] = $description;
  }

  public function validate($value): ValidationResult {
    $this->resetMessages();

    if ($this->isRequired() && is_null($value) || $value === '') {
      $this->addMessage('Required');
      return $this->validationResult();
    }

    $this->validateAginstRegexPatterns($value);

    return $this->validationResult();
  }

  protected function addMessage($message) {
    array_push($this->messages, $message);
  }

  protected function validationResult() {
    if (count($this->messages))
      return new Validation\InvalidResult($this->messages);

    return new Validation\ValidResult();
  }

  protected function isValidRegexPattern($pattern) {
    return @preg_match($pattern, null) === false;
  }

  protected function validateAginstRegexPatterns($value) {
    foreach ($this->regex as $pattern=>$description) {
      $match = preg_match($pattern, $value, $matches);
      if ($match === 0)
        $this->addMessage($description);
    }
  }

  protected function resetMessages() {
    $this->messages = [];
  }
}