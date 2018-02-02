<?php

namespace Logikos\Util;

class Pair {
  private $key;
  private $value;

  public function __construct($key, $value) {
    $this->key = $this->getScalar($key);
    $this->value = $value;
  }

  public function getKey() {
    return $this->key;
  }

  public function getValue() {
    return $this->value;
  }

  private function getScalar($key) {
    $this->validateNotNull($key);
    $this->validateNotObjectOrHasToString($key);

    if (is_numeric($key) || is_string($key))
      return $key;

    return (string) $key;
  }

  /**
   * @param $key
   * @throws InvalidTypeException
   */
  private function validateNotNull($key) {
    if (is_null($key))
      throw new InvalidTypeException('Can not use null for a key');
  }

  /**
   * @param $key
   * @throws InvalidTypeException
   */
  private function validateNotObjectOrHasToString($key) {
    if (is_object($key) && !method_exists($key, '__toString'))
      throw new InvalidTypeException('Object keys must implement __toString');
  }
}