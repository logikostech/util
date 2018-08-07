<?php

namespace Logikos\Util\Config;

use Logikos\Util\Config;

class ImmutableConfig extends Config {

  protected function onConstruct() {
    $this->lock();
  }

  protected function subConfig($arrayConfig = []) {
    return new self($arrayConfig);
  }

  public function with($key, $value) {
    $values = $this->rawValues();
    $values[$key] = $value;
    return new self($values);
  }
}