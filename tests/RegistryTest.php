<?php

namespace LogikosTest\Util;

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

    $this->assertTrue($reg->isset('a'));
    $this->assertTrue($reg->isset('b'));
    $this->assertFalse($reg->isset('c'));
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

  public function testCount() {
    $reg = new Registry();
    $reg->a = 'foo';
    $this->assertSame(1, count($reg));
    $this->assertSame(1, $reg->count());
  }

  public function testGetOffsetThatDoesNotExistThrowsException() {
    $reg = new Registry();
    $this->expectException(\OutOfBoundsException::class);
    $reg['foo'];
  }

  public function testGetRawValuesFromSubclass() {
    $sut = new class extends Registry {
      public function exposedRawValues() {
        return $this->rawValues();
      }
    };
    $sut->foo = 'bar';
    $this->assertEquals(
        ['foo'=>'bar'],
        $sut->exposedRawValues()
    );
  }

  public function testOffsetExistsWhenSetToNull() {
    $r = new Registry();
    $this->assertFalse($r->offsetExists('a'));
    $r->offsetSet('a', null);
    $this->assertTrue($r->offsetExists('a'));
  }

}