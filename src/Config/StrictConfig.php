<?php

namespace Logikos\Util\Config;

use Logikos\Util\Config;

abstract class StrictConfig extends Config {

  /** @var  Field[] */
  private $_fields = [];

  public final function onConstruct() {
    $this->initialize();
  }

  abstract protected function initialize();

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

  protected function addFields(Field ...$fields) {
    $this->_fields = $fields;
  }

  protected function fieldValidationMessages(Field $field) {
    return $this->fieldValidationResult($field)->getMessages();
  }

  protected function isFieldValid(Field $field) {
    return $this->fieldValidationResult($field)->isValid();
  }

  /**
   * @param Field $field
   * @return Field\Validation\Result
   */
  protected function fieldValidationResult(Field $field): Field\Validation\Result {
    return $field->validate($this->get($field->getName()));
  }

}