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
 *  // or
 *  $registry['something'] = 'something';
 *
 *  // Get value
 *  $value = $registry->something;
 *  // or
 *  $value = $registry['something'];
 *
 *  // Check if the key exists
 *  $exists = isset($registry->something);
 *  // or
 *  $exists = isset($registry['something']);
 *
 *  // Unset
 *  unset($registry->something);
 *  // or
 *  unset($registry['something']);
 *</code>
 */
abstract class Registry implements \ArrayAccess, \Countable, \Iterator {
  protected $_data;

  /**
   * Registry constructor
   */
  public function __construct() {
    $this->_data = [];
  }

  /**
   * Checks if the element is present in the registry
   */
  public function offsetExists($offset) {
    return isset($this->_data[$offset]);
  }

  /**
   * Returns an index in the registry
   */
  public final function offsetGet($offset) {
    return $this->_data[$offset];
  }

  /**
   * Sets an element in the registry
   */
  public final function offsetSet($offset, $value) {
    $this->_data[$offset] = $value;
  }

  /**
   * Unsets an element in the registry
   */
  public final function offsetUnset($offset) {
    unset($this->_data[$offset]);
  }

  /**
   * Checks how many elements are in the register
   */
  public final function count() {
    return count($this->_data);
  }

  /**
   * Moves cursor to next row in the registry
   */
  public final function next() {
    next($this->_data);
  }

  /**
   * Gets pointer number of active row in the registry
   */
  public final function key() {
    return key($this->_data);
  }

  /**
   * Rewinds the registry cursor to its beginning
   */
  public final function rewind() {
    reset($this->_data);
  }

  /**
   * Checks if the iterator is valid
   */
  public function valid() {
    return key($this->_data) !== null;
  }

  /**
   * Obtains the current value in the internal iterator
   */
  public function current() {
    return current($this->_data);
  }

  /**
   * Sets an element in the registry
   */
  public final function __set($key, $value) {
    $this->offsetSet($key, $value);
  }

  /**
   * Returns an index in the registry
   */
  public final function __get($key) {
    return $this->offsetGet($key);
  }

  public final function __isset($key) {
    return $this->offsetExists($key);
  }

  public final function __unset($key) {
    $this->offsetUnset($key);
  }
}