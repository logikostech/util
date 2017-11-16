<?php

namespace Logikos\Util\Tests\Config;

use Logikos\Util\CanNotMutateException;
use Logikos\Util\Config as AbstractConfig;
use Logikos\Util\Config\ImmutableConfig as Config;

class ImmutableConfigTest extends TestCase {

  # Create & Count
  public function testInstanceOfInterfaces() {
    $conf = new Config(['a' => 'foo']);
    $this->assertInstanceOf(AbstractConfig::class, $conf);
  }

  public function testIsLocked() {
    $conf = new Config(['a' => 'foo']);
    $this->assertTrue($conf->isLocked());
  }

  # Set
  public function testCanNotAssignAsArray() {
    $this->expectException(CanNotMutateException::class);
    $conf = new Config(['a' => 'foo']);
    $conf['b'] = 'bar';
  }

  public function testCanNotAssignAsProperty() {
    $this->expectException(CanNotMutateException::class);
    $conf = new Config(['a' => 'foo']);
    $conf->b = 'bar';
  }

  public function testCanNotReCallConstruct() {
    $this->expectException(CanNotMutateException::class);
    $conf = new Config(['a' => 'foo']);
    $conf->__construct(['b' => 'bar']);
  }

  public function testCanNotSet() {
    $conf = new Config(['a' => 'foo']);
    if (method_exists($conf, 'set')) {
      $this->expectException(CanNotMutateException::class);
      $conf->set('b', 'bar');
    }
    else $this->assertFalse(method_exists($conf, 'set'));
  }


  # Unset
  public function testCanNotUnsetPropertyAccess() {
    $this->expectException(CanNotMutateException::class);
    $conf = new Config(['a' => 'foo']);
    unset($conf->a);
  }

  public function testCanNotUnsetArrayAccess() {
    $this->expectException(CanNotMutateException::class);
    $conf = new Config(['a' => 'foo']);
    unset($conf['a']);
  }


  # Mutate
  public function testWithKeyValue() {
    $conf1 = new Config(['a' => 'foo']);
    $conf2 = $conf1->with('b', 'bar');
    $this->assertKeyNotExists($conf1, 'b');
    $this->assertConfValue($conf1, 'a', 'foo');
    $this->assertConfValue($conf2, 'b', 'bar');
  }

  public function testCanNotMutateClones() {
    $conf1 = new Config(['a' => 'foo']);
    $conf2 = $conf1->with('b', 'bar');
    $this->expectException(CanNotMutateException::class);
    $conf2->offsetSet('a', 'baz');
  }

  public function testCanNotMerge() {
    $conf = new Config(['a' => 'foo']);
    if (method_exists($conf, 'merge')) {
      $this->expectException(CanNotMutateException::class);
      $conf->merge();
    }
    else $this->assertFalse(method_exists($conf, 'merge'));
  }
}
