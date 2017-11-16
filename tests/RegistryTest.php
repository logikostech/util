<?php

namespace Logikos\Util\Tests;

use Logikos\Util\Registry;

class RegistryTest extends TestCase {
  public function testIsset() {
    $reg = new Registry();
    $reg->a = 'foo';
    $reg['b'] = 'bar';
    $this->assertTrue(isset($reg->a));
    $this->assertTrue(isset($reg['b']));
    $this->assertFalse(isset($reg->c));
    $this->assertFalse(isset($reg['c']));
  }

  public function testSetGet() {
    $reg = new Registry();
    $reg->a = 'foo';
    $reg['b'] = 'bar';
    $this->assertEquals('foo', $reg->a);
    $this->assertEquals('bar', $reg['b']);
    $this->assertEquals(2, count($reg));
  }

  public function testUnset() {
    $reg = new Registry();
    $reg->a = 'foo';
    $reg['b'] = 'bar';
    unset($reg['a']);
    unset($reg->b);
    $this->assertFalse(isset($reg->a));
    $this->assertFalse(isset($reg['b']));
  }

  public function testIterate() {
    $reg = new Registry();
    $reg->a = 'foo';
    $reg->b = 'bar';
    $i = 0;
    foreach ($reg as $k => $v) $i++;
    $this->assertEquals(2, $i);
  }
}