<?php

namespace Logikos\Util;

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
    if ($this->pathStartsWithConfig($path, $delimiter))
      return $this->evalSubPath($path, $delimiter, $default);

    return $this->get($this->getFirstToken($path, $delimiter), $default);
  }

  private function pathStartsWithConfig($path, $delimiter) {
    return $this->getFirstTokenValue($path, $delimiter) instanceof self;
  }

  private function getFirstTokenValue($path, $delimiter) {
    return $this->get($this->getFirstToken($path, $delimiter));
  }

  private function getFirstToken($path, $delimiter) {
    return $this->subtok($path, $delimiter, 0, 1);
  }

  private function evalSubPath($path, $delimiter, $default) {
    return $this->getFirstTokenValue($path, $delimiter)->path(
        $this->subtok($path, $delimiter, 1),
        $default,
        $delimiter
    );
  }

  /**
   * subtok(string, delimiter, offset, length)
   *
   * Usage:
   *  subtok('a.b.c.d.e','.',0)     = 'a.b.c.d.e'
   *  subtok('a.b.c.d.e','.',0,2)   = 'a.b'
   *  subtok('a.b.c.d.e','.',2,1)   = 'c'
   *  subtok('a.b.c.d.e','.',2,-1)  = 'c.d'
   *  subtok('a.b.c.d.e','.',-4)    = 'b.c.d.e'
   *  subtok('a.b.c.d.e','.',-4,2)  = 'b.c'
   *  subtok('a.b.c.d.e','.',-4,-1) = 'b.c.d'
   *
   * @param  string   $string    The input string
   * @param  string   $delimiter The boundary string
   * @param  int      $offset    starting position, like in substr
   * @param  int|null $length    length, like in substr
   * @return string
   */
  function subtok($string, $delimiter, $offset, $length = NULL) {
    return implode($delimiter, array_slice(explode($delimiter, $string), $offset, $length));
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