<?php

namespace LogikosTest\Util\Config;

use Logikos\Util\Config as AbstractConfig;
use Logikos\Util\Config\MutableConfig as Config;

class MutableConfigTest extends TestCase {

  # Create & Count
  public function testInstanceOfInterfaces() {
    $conf = new Config(['a' => 'foo']);
    $this->assertInstanceOf(AbstractConfig::class, $conf);
  }

  public function testIsNotLocked() {
    $conf = new Config(['a' => 'foo']);
    $this->assertFalse($conf->isLocked());
  }

  # Set
  public function testSet() {
    $conf = new Config(['a' => 'foo']);
    $conf->set('b', 'bar');
    $conf->set('jake', ['name' => 'Jake', 'age' => 30]);
    $this->assertEquals('bar', $conf->offsetGet('b'));
  }

  public function testAssignmentWithSetOfArrayIsConfigObject() {
    $conf = new Config();
    $conf->set('jake', ['name' => 'Jake', 'age' => 30]);
    $this->assertTrue($conf['jake'] instanceof Config);
  }

  public function testHas() {
    $conf = new Config();
    $this->assertFalse($conf->has('a'));
    $conf['a'] = 'bar';
    $this->assertTrue($conf->has('a'));
  }

  # Unset
  public function testCanNotUnsetPropertyAccess() {
    $conf = new Config(['a' => 'foo']);
    unset($conf->a);
    $this->assertFalse($conf->has('a'));
  }

  public function testCanNotUnsetArrayAccess() {
    $conf = new Config(['a' => 'foo']);
    unset($conf['a']);
    $this->assertFalse($conf->has('a'));
  }
}