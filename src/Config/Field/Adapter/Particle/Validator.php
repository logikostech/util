<?php

namespace Logikos\Util\Config\Field\Adapter\Particle;

use Particle\Validator as Particle;

class Validator extends Particle\Validator {
  /**
   * @param string $key
   * @param null   $name
   * @param bool   $allowEmpty
   * @return Chain
   */
  public function required($key, $name = null, $allowEmpty = false) {
    /** @var Chain $chain */
    $chain = parent::required($key, $name, $allowEmpty);
    return $chain;
  }

  /**
   * @param string $key
   * @param null   $name
   * @param bool   $allowEmpty
   * @return Chain
   */
  public function optional($key, $name = null, $allowEmpty = true) {
    /** @var Chain $chain */
    $chain = parent::optional($key, $name, $allowEmpty);
    return $chain;
  }

  protected function buildChain($key, $name, $required, $allowEmpty) {
    return new Chain($key, $name, $required, $allowEmpty);
  }
}