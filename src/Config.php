<?php

namespace Logikos\Util;

/**
 * This is largely inspired by \Phalcon\Config - https://docs.phalconphp.com/hr/3.2/api/Phalcon_Config
 * NOTICE: \Phalcon\Config will be much faster than this class and you are encouraged to use it
 *
 *<code>
 * $config = new \Logikos\Util\Config([
 *     "database" => [
 *         "adapter"  => "Mysql",
 *         "host"     => "localhost",
 *         "username" => "scott",
 *         "password" => "cheetah"
 *     ]
 * ]);
 *
 * // export as array
 * $config->toArray();
 *
 * // Set/alter value
 * $config->database->dbname     = 'something';
 * // or
 * $config['database']['dbname'] = 'something';
 *
 * // Get value
 * $value = $config->database->host;
 * // or
 * $value = $config['database']['host'];
 * // or
 * $value = $config->get('database')->get('host');
 *
 * // Check if the key exists
 * $exists = isset($config->something);
 * // or
 * $exists = isset($config['something']);
 * // or
 * $exists = $config->has('something');
 *
 * // Unset
 * unset($config->something);
 * // or
 * unset($config['something']);
 *</code>
 */
abstract class Config implements \ArrayAccess, \Countable, \Iterator {
  private $locked   = false;
  private $values   = [];

  public function __construct(array $arrayConfig = []) {
    foreach($arrayConfig as $key => $value)
      $this->offsetSet($key, $value);
    if (method_exists($this, 'onConstruct'))
      $this->onConstruct();
  }

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
    $array = [];
    foreach ($this->values as $key => $value) {
      if (is_object($value) && method_exists($value, 'toArray')) {
        $array[$key] = $value->toArray();
        continue;
      }
      $array[$key] = $value;
    }
    return $array;
  }

  public function path($path, $default = null, $delimiter = '.') {
    $tokens = explode($delimiter, $path);
    if ($token = array_shift($tokens)) {
      if ($this->get($token) instanceof self)
        return $this[$token]->path(implode('.', $tokens), $default);
      return $this->get($token, $default);
    }
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

  public static function __set_state(array $data) : Config {
    return new static($data);
  }


  # For Internal Use
  protected function lock() {
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
      throw new Config\CanNotMutateException();
  }
}