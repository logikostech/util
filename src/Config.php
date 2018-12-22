<?php

namespace Logikos\Util;

use Logikos\Util\Config\PathEval;

/**
 * This is largely inspired by \Phalcon\Config - https://docs.phalconphp.com/hr/3.2/api/Phalcon_Config
 * NOTICE: \Phalcon\Config will be much faster than this class and you are encouraged to use it
 * @see ../docs/config/README.md
 */
abstract class Config extends Registry {
  private $locked   = false;

  /**
   * Config constructor.
   * @param array $arrayConfig
   */
  public function __construct(array $arrayConfig = []) {
    parent::__construct();

    $this->import($this->defaults());
    $this->import($arrayConfig);
    $this->onConstruct();
  }

  public function import(array $data) {
    foreach ($data as $key=>$value) $this->offsetSet($key, $value);
  }

  // override this to have a base config
  protected function defaults(): array {
    return [];
  }

  // override this if you want to
  protected function onConstruct() {}

  public function isLocked() {
    return $this->locked;
  }

  public function get($key, $default = null) {
    return $this->offsetExists($key) ? $this->offsetGet($key) : $default;
  }

  public function has($key) {
    return $this->offsetExists($key);
  }

  public function toArray() {
    return array_map(
        function ($value) {
          // $value may be another Config object, if so then we toArray() it also.
          return $this->hasToArray($value) ? $value->toArray() : $value;
        },
        $this->rawValues()
    );
  }

  private function hasToArray($value): bool {
    return is_object($value)
        && method_exists($value, 'toArray');
  }

  public function path($path, $default = null, $delimiter = '.') {
    return (new PathEval($this))->find($path, $default, $delimiter);
  }


  # ArrayAccess

  /**
   * @param $offset
   * @param $value
   * @throws CanNotMutateException
   */
  public function offsetSet($offset, $value) {
    $this->blockIfLocked();
    parent::offsetSet($offset, $this->settableValue($value));
  }

  protected function settableValue($value) {
    if (is_array($value)) return $this->subConfig($value);
    return $value;
  }

  protected function subConfig($arrayConfig = []) {
    return new class($arrayConfig) extends Config {};
  }

  /**
   * @param $offset
   * @throws CanNotMutateException
   */
  public function offsetUnset($offset) {
    $this->blockIfLocked();
    parent::offsetUnset($offset);
  }


  public static function __set_state(array $data): Config {
    return new static($data);
  }

  public function lock() {
    $this->locked = true;
  }

  protected function isConfigObject($value) {
    return $value instanceof Config;
  }

  /**
   * @throws CanNotMutateException
   */
  private function blockIfLocked() {
    if ($this->isLocked())
      throw new CanNotMutateException();
  }
}