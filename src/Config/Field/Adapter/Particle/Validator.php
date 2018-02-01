<?php

namespace Logikos\Util\Config\Field\Adapter\Particle;

use Particle\Validator as Particle;

class Validator extends Particle\Validator {
  /**
   * @param string $key
   * @param null   $name
   * @param bool   $allowEmpty
   * @return Chain|Particle\Chain
   */
  public function required($key, $name = null, $allowEmpty = false) {
    return parent::required($key, $name, $allowEmpty);
  }

  /**
   * @param string $key
   * @param null   $name
   * @param bool   $allowEmpty
   * @return Chain|Particle\Chain
   */
  public function optional($key, $name = null, $allowEmpty = true) {
    return parent::optional($key, $name, $allowEmpty);
  }

  protected function buildChain($key, $name, $required, $allowEmpty) {
    return new Chain($key, $name, $required, $allowEmpty);
  }
}