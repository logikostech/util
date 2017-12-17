<?php

namespace Logikos\Util\Config;

use Logikos\Util\Config;

abstract class StrictConfig extends Config {

  /** @var  Option[] */
  private $options = [];


  protected function addOption(Option $option) {
    $this->options[$option->getName()] = $option;
  }

  public function offsetSet($offset, $value) {
    if (!array_key_exists($offset, $this->options))
      throw new OptionNotDefinedException();

    if (!$this->options[$offset]->isValidValue($value))
      throw new Config\Option\InvalidOptionValueException();

    parent::offsetSet($offset, $value);
  }

  public function isValid() {
    foreach ($this->options as $option) {
      if (!$this->isOptionValueValid($option))
        return false;
    }
    return true;
  }

  public function validationMessages() {
    $messages = [];
    foreach ($this->options as $option) {
      if (!$this->isOptionValueValid($option)) {
        $messages[$option->getName()] = $option->validationMessages($this->get($option->getName(), null));
      }
    }
    return $messages;
  }

  private function getValueOrNull(Option $option) {
    return $this->offsetExists($option->getName())
        ? $this->offsetGet($option->getName())
        : null;
  }

  private function isOptionValueValid(Option $option) {
    return $option->isValidValue($this->getValueOrNull($option));
  }


}