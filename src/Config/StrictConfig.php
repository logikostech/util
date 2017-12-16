<?php

namespace Logikos\Util\Config;

use Logikos\Util\Config;

abstract class StrictConfig extends Config {

  /** @var  Option[] */
  private $options = [];

  public function addOption(Option $option) {
    $this->options[$option->getName()] = $option;
  }

  public function offsetSet($offset, $value) {
    if (!array_key_exists($offset, $this->options))
      throw new OptionNotDefinedException();

    parent::offsetSet($offset, $value);
  }

}