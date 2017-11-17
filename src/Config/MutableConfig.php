<?php

namespace Logikos\Util\Config;

use Logikos\Util\Config;

class MutableConfig extends Config {
  # Set
  public function set($offset, $value) {
    $this->offsetSet($offset, $value);
  }

  # Merge
  public function merge($b) {
    foreach ($b as $key=>$value) {
      $this->_mergeKeyValue($key, $value);
    }
  }

  private function _mergeKeyValue($key, $value) {
    if ($this->isConfigObjectMerge($key, $value)) {
      $this->offsetGet($key)->merge($value);
      return;
    }
    $this->offsetSet($this->keyForMerge($key), $value);
  }

  private function isConfigObjectMerge($key, $value) {
    return $this->offsetExists($key)
        && $this->isConfigObject($value)
        && $this->isConfigObject($this->{$key});
  }

  private function keyForMerge($key) {
    return is_numeric($key) ? $this->nextNumericIndex() : $key;
  }

  private function nextNumericIndex() {
    $numericKeys = array_filter(array_keys($this->rawValues()), 'is_numeric');
    if (count($numericKeys) === 0) return 0;
    return max($numericKeys) + 1;
  }
}