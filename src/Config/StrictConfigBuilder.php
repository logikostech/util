<?php

namespace Logikos\Util\Config;

use Logikos\Util\StrictConfig;

class StrictConfigBuilder {

  public function build() : StrictConfig {
    return new StrictConfig();
  }
}