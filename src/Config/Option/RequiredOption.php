<?php

namespace Logikos\Util\Config\Option;

use Logikos\Util\Config\OptionDefinition;

class RequiredOption extends OptionDefinition {
  public function isRequired()  { return true; }

  public function validationMessages($value = null) {
    $messages = parent::validationMessages($value);

    if (is_null($value) && count($messages) === 0)
      array_push($messages, 'Required field');

    return $messages;
  }
}