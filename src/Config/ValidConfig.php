<?php

namespace Logikos\Util\Config;

use Logikos\Util\Config;

abstract class ValidConfig extends Config {
  public final function onConstruct() {
    $this->initialize();
    $this->lock();
  }

  abstract protected function initialize();
}