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
class Config implements \ArrayAccess, \Countable  {

  public function __construct(array $arrayConfig = []) {
    foreach($arrayConfig as $key => $value) {
      $this->offsetSet($key, $value);
    }
  }

  public function count() {
    return count(get_object_vars($this));
  }

  public function get($key, $default = null) {
    return $this->has($key) ? $this->offsetGet($key) : $default;
  }

  public function has($key) {
    return $this->offsetExists($key);
  }

  public function offsetGet($offset) {
    return $this->{strval($offset)};
  }

  public function offsetExists($offset) {
    return isset($this->{strval($offset)});
  }


  public function offsetSet($offset, $value) {
    if (is_array($value))
      $this->{strval($offset)} = new static($value);

    else
      $this->{strval($offset)} = $value;
  }

  public function offsetUnset($offset) {
    unset($this->{strval($offset)});
  }

  public function toArray() {
    $array = [];
    foreach (get_object_vars($this) as $key => $value) {
      if (is_object($value) && method_exists($value, 'toArray')) {
        $array[$key] = $value->toArray();
        continue;
      }
      $array[$key] = $value;
    }
    return $array;
  }

  public function merge($b) {
    foreach ($b as $key=>$value) {
      $this->_mergeKeyValue($key, $value);
    }
  }

  private function _mergeKeyValue($key, $value) {
    if ($this->isConfigObjectMerge($key, $value)) {
      $this->{$key}->merge($value);
      return;
    }
    $this->offsetSet($this->keyForMerge($key), $value);
  }

  private function isConfigObjectMerge($key, $value) {
    if (!$this->has($key)) return false;
    if (!$this->isConfigObject($value)) return false;
    if (!$this->isConfigObject($this->{$key})) return false;
    return true;
  }

  private function isConfigObject($value) {
    return $value instanceof Config;
  }

  private function keyForMerge($key) {
    return is_numeric($key) ? $this->nextNumericIndex() : $key;
  }

  private function nextNumericIndex() {
    $numericKeys = array_filter(array_keys(get_object_vars($this)), 'is_numeric');
    if (count($numericKeys) === 0) return 0;
    return max($numericKeys) + 1;
  }

  public function __set($name, $value) {
    $this->offsetSet($name, $value);
  }

  public static function __set_state(array $data) : Config {
    return new self($data);
  }

  public function path($path, $default = null, $delimiter = '.') {
    $tokens = explode($delimiter, $path);
    if ($token = array_shift($tokens)) {
      if ($this->get($token) instanceof self)
        return $this[$token]->path(implode('.', $tokens), $default);
      return $this->get($token, $default);
    }
  }
}