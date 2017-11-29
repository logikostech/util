<?php

namespace Logikos\Util\Tests;

use Logikos\Util\InvalidTypeException;
use Logikos\Util\Pair;

class PairTest extends TestCase {
  public function testCanCreate() {
    $pair = new Pair('key', 'value');
    $this->assertNotEmpty($pair);
  }

  public function testGetValue() {
    $pair = new Pair('key', 'value');
    $this->assertSame('value', $pair->getValue());
  }

  public function testGetKey() {
    $pair = new Pair('foo', 'bar');
    $this->assertSame('foo', $pair->getKey());
    $this->assertSame('bar', $pair->getValue());
  }

  public function testNumericKey() {
    $this->assertSame(
        1,
        (new Pair(1, 'value'))->getKey()
    );
  }

  public function testStringKey() {
    $this->assertSame(
        'string',
        (new Pair('string', 'value'))->getKey()
    );
  }

  public function testObjectKeyWithToString() {
    $key = new class() { public function __toString() { return 'foo'; }};
    $this->assertSame(
        'foo',
        (new Pair($key, 'value'))->getKey()
    );
  }

  public function testNullKeyThrowsException() {
    $this->expectException(InvalidTypeException::class);
    new Pair(null, 'bar');
  }

  public function testObjectKeyWithoutToStringThrowsException() {
    $this->expectException(InvalidTypeException::class);
    $key = new class() { };
    new Pair($key, 'bar');
  }
}