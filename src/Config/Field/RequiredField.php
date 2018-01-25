<?php

namespace Logikos\Util\Config\Field;

use Logikos\Util\Config\Field;

class RequiredField implements Field {
  private $messages = [];

  public function validate($value): Field\ValidationResult {
    if (is_null($value) || $value === '')
      $this->addMessage('Required');

    return $this->validationResult();
  }

  protected function addMessage($message) {
    array_push($this->messages, $message);
  }

  private function validationResult() {
    if (count($this->messages))
      return new Field\Validation\InvalidResult($this->messages);
    return new Field\Validation\ValidResult();
  }
}