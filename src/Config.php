<?php

namespace Logikos\Util;

use Logikos\Util\Config\PathEval;

/**
 * This is largely inspired by \Phalcon\Config - https://docs.phalconphp.com/hr/3.2/api/Phalcon_Config
 * NOTICE: \Phalcon\Config will be much faster than this class and you are encouraged to use it
 * @see ../docs/config/README.md
 */
abstract class Config implements \ArrayAccess, \Countable, \Iterator {
  private $locked   = false;
  private $values   = [];

  public function __construct(array $arrayConfig = []) {
    foreach($arrayConfig as $key => $value)
      $this->offsetSet($key, $value);

    $this->onConstruct();
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
          return $this->hasToArray($value) ? $value->toArray() : $value;
        },
        $this->values
    );
  }

  private function hasToArray($value): bool {
    return is_object($value) && method_exists($value, 'toArray');
  }

  public function path($path, $default = null, $delimiter = '.') {
    return (new PathEval($this))->find($path, $default, $delimiter);
  }

  # Countable
  public function count() {
    return count($this->values);
  }


  # ArrayAccess
  public function offsetExists($offset) {
    return array_key_exists($offset, $this->values);
  }

  public function offsetGet($offset) {
    if (!$this->offsetExists($offset))
      throw new \OutOfBoundsException("offset '{$offset}' does not exist");
    return $this->values[$offset];
  }

  public function offsetSet($offset, $value) {
    $this->blockIfLocked();
    $this->values[strval($offset)] = is_array($value)
        ? new static($value)
        : $value;
  }

  public function offsetUnset($offset) {
    $this->blockIfLocked();
    unset($this->values[strval($offset)]);
  }


  # Iterator
  public function rewind()  { return reset($this->values);        }
  public function key()     { return key($this->values);          }
  public function current() { return current($this->values);      }
  public function next()    { return next($this->values);         }
  public function valid()   { return key($this->values) !== null; }


  # Magic Property Access
  public function __set($offset, $value) { $this->offsetSet($offset, $value);   }
  public function __unset($offset)       { $this->offsetUnset($offset);         }
  public function __get($offset)         { return $this->offsetGet($offset);    }
  public function __isset($offset)       { return $this->offsetExists($offset); }

  /**
   * @param array $data
   * @return static
   */
  public static function __set_state(array $data): Config {
    return new static($data);
  }

  public function lock() {
    $this->locked = true;
  }

  protected function rawValues() {
    return $this->values;
  }

  protected function isConfigObject($value) {
    return $value instanceof Config;
  }

  private function blockIfLocked() {
    if ($this->isLocked())
      throw new CanNotMutateException();
  }
}