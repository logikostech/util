<?php

namespace Logikos\Util\Config;

class StrictConfigBuilder {

  /** @var  Option[] */
  private $options;

  public function build() : StrictConfig {
    return new StrictConfig();
  }

  public function addOption(Option $option) {
    $this->options[$option->getName()] = $option;
  }
}