<?php

namespace Logikos\Util\Config;

use Logikos\Util\Config;

abstract class ValidConfig extends Config {
  /** @var  Field[] */
  private $_fields = [];

  public final function onConstruct() {
    $this->initialize();
  }

  public function isValid() {
    foreach ($this->_fields as $field) {
      if (!$this->isFieldValid($field))
        return false;
    }
    return true;
  }

  public function validate() {
    if (!$this->isValid())
      throw new InvalidConfigStateException($this);
  }

  public function validationMessages() {
    $failures = [];
    foreach ($this->_fields as $field) {
      if (!$this->isFieldValid($field))
        $failures[$field->getName()] = $this->fieldValidationMessages($field);
    }
    return $failures;
  }

  private function fieldValidationMessages(Field $field) {
    return $field->validate($this->get($field->getName()))->getMessages();
  }

  private function isFieldValid(Field $field) {
    return $field->validate($this->get($field->getName()))->isValid();
  }

  abstract protected function initialize();

  protected function addFields(Field ...$fields) {
    $this->_fields = $fields;
  }

}