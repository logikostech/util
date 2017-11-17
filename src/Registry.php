<?php

namespace Logikos\Util;

/**
 * This is a php version of \Phalcon\Registry
 * NOTICE: \Phalcon\Registry will be much faster than this class and you are encouraged to use it
 *
 *<code>
 *  $registry = new \Phalcon\Registry();
 *
 *  // Set value
 *  $registry->something = 'something';
 *  $registry['something'] = 'something';
 *
 *  // Get value
 *  $value = $registry->something;
 *  $value = $registry['something'];
 *
 *  // Check if the key exists
 *  $exists = isset($registry->something);
 *  $exists = isset($registry['something']);
 *
 *  // Unset
 *  unset($registry->something);
 *  unset($registry['something']);
 *</code>
 */
class Registry implements \ArrayAccess, \Countable, \Iterator {
  private $values;

  public function __construct(array $data = []) {
    $this->values = $data;
  }

  # Countable
  public function count() {
    return count($this->values);
  }

  # ArrayAccess
  public function offsetGet($offset)       { return $this->requireOffset($offset); }
  public function offsetExists($offset)    { return isset($this->values[$offset]); }
  public function offsetSet($offset, $val) { $this->values[$offset] = $val;        }
  public function offsetUnset($offset)     { unset($this->values[$offset]);        }

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

  protected function requireOffset($offset) {
    if (!$this->offsetExists($offset))
      throw new \OutOfBoundsException("offset '{$offset}' does not exist");

    return $this->values[$offset];
  }

  protected function rawValues() {
    return $this->values;
  }
}