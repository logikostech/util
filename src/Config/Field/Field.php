<?php

namespace Logikos\Util\Config\Field;

use Innmind\Immutable\Stream;
use Logikos\Util\Config\Field as FieldInterface;
use Logikos\Util\Config\Field\Validation\Result as ValidationResult;
use Logikos\Util\Config\Field\Validation\Validator;

abstract class Field implements FieldInterface {

  protected $name;
  protected $messages = [];

  /** @var Validator\Regex[] */
  protected $regex    = [];
  protected $callable = [];

  public function __construct($name) {
    $this->name = $name;
  }

  abstract public function isRequired() : bool;

  public function getName() {
    return $this->name;
  }

  public function addPattern($pattern, $description) {
    if (!$this->regex)
      $this->regex = new Stream(Validator\Regex::class);

    $this->regex = $this->regex->add(new Validator\Regex($pattern, $description));
  }

  public function addCallable(callable $callable, $description) {
    $this->callable[] = (object) [
        'callable'    => $callable,
        'description' => $description
    ];
  }

  public function validate($value): ValidationResult {
    $this->resetMessages();

    if ($this->isRequired() && $this->isEmpty($value)) {
      $this->addMessage('Required');
      return $this->validationResult();
    }

    $this->validateAgainstRegexPatterns($value);
    $this->validateAgainstCallables($value);

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

  protected function validateAgainstRegexPatterns($value) {
    foreach ($this->regex as $regexValidator) {
      if (!$regexValidator->validate($value))
        $this->addMessage($regexValidator->getDescription());
    }
  }

  protected function validateAgainstCallables($value) {
    foreach ($this->callable as $item) {
      if (!call_user_func($item->callable, $value))
        $this->addMessage($item->description);
    }
  }

  protected function resetMessages() {
    $this->messages = [];
  }

  protected function isEmpty($value) {
    return is_null($value) || $value === '';
  }
}