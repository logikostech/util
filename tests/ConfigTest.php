<?php

namespace Logikos\Util\Tests;

use Logikos\Util\Config;
use Logikos\Util\Tests\Config\TestCase;

class ConfigTest  extends TestCase {

  # Create & Count
  public function testInstanceOfInterfaces() {
    $conf = $this->getConfig();
    $this->assertInstanceOf(\ArrayAccess::class, $conf);
    $this->assertInstanceOf(\Countable::class,   $conf);
    $this->assertInstanceOf(\Iterator::class,    $conf);
  }

  public function testInitWithNothingCreatesEmptyConfig() {
    $conf = $this->getConfig();
    $this->assertEquals(0, $conf->count());
    $this->assertEquals(0, count($conf));
  }

  public function testInitWithEmptyArray() {
    $conf = $this->getConfig([]);
    $this->assertEquals(0, count($conf));
  }

  public function testWhenOneItemInArray_CountIsOne() {
    $conf = $this->getConfig(['a' => 'foo']);
    $this->assertEquals(1, count($conf));
  }

  public function testInnerArraysBecomeConfigObjects() {
    $data = [
        'john' => [
            'name' => 'John',
            'age'  => 50
        ]
    ];
    $conf = $this->getConfig($data);
    $this->assertTrue($conf['john'] instanceof Config);
  }

  public function testDoesNotMutateObjects() {
    $data = [
        'john' => (object)[
            'name' => 'John',
            'age'  => 50
        ],
        'jane' => (object)[
            'name' => 'Jane',
            'age'  => 40
        ],
    ];
    $conf = $this->getConfig($data);
    $this->assertSame($data['john'], $conf['john']);
  }


  # Isset
  public function testHasKey() {
    $conf = $this->getConfig(['a' => 'foo']);
    $this->assertTrue($conf->has('a'));
    $this->assertFalse($conf->has('b'));
  }

  public function testIssetArrayAccess() {
    $conf = $this->getConfig(['a' => 'foo']);
    $this->assertTrue(isset($conf['a']));
    $this->assertFalse(isset($conf['b']));
  }

  public function testIssetPropertyAccess() {
    $conf = $this->getConfig(['a' => 'foo']);
    $this->assertTrue(isset($conf->a));
    $this->assertFalse(isset($conf->b));
  }


  # Get
  public function testWhenNotSet_GetReturnsNull() {
    $conf = $this->getConfig(['a' => 'foo']);
    $this->assertNull($conf->get('b'));
  }

  public function testWhenNotSet_GetReturnsProvidedDefault() {
    $conf = $this->getConfig(['a' => 'foo']);
    $this->assertEquals('bar', $conf->get('b', 'bar'));
  }

  public function testGet() {
    $conf = $this->getConfig(['a' => 'foo', 'b' => 'bar']);
    $this->assertEquals('foo', $conf->get('a'));
    $this->assertEquals('bar', $conf->get('b'));
  }

  public function testCanGetLikeArray() {
    $conf = $this->getConfig(['a' => 'foo']);
    $this->assertEquals('foo', $conf['a']);
  }

  public function testCanGetAsObjectProperty() {
    $conf = $this->getConfig(['a' => 'foo']);
    $this->assertEquals('foo', $conf->a);
  }

  public function testWhenGettingUnsetOffset_ExpectException() {
    $conf = $this->getConfig(['a' => 'foo']);
    $this->expectException(\OutOfBoundsException::class);
    $conf['b'];
  }

  public function testWhenGettingUnsetOffsetAsProperty_ExpectException() {
    $conf = $this->getConfig(['a' => 'foo']);
    $this->expectException(\OutOfBoundsException::class);
    $conf->b;
  }
  

  # Set
  public function testSetAsArray() {
    $conf = $this->getConfig(['a' => 'foo']);
    $conf['b'] = 'bar';
    $this->assertEquals('bar', $conf['b']);
  }

  public function testSetAsProperty() {
    $conf = $this->getConfig(['a' => 'foo']);
    $conf->b = 'bar';
    $this->assertEquals('bar', $conf->b);
  }

  public function testAssignmentOfArrayIsConfigObject() {
    $conf = $this->getConfig();
    $conf->john      = ['name' => 'John', 'age' => 50];
    $conf['jane']    = ['name' => 'Jane', 'age' => 40];
    $this->assertTrue($conf['john'] instanceof Config);
    $this->assertTrue($conf['jane'] instanceof Config);
  }


  # Unset
  public function testCanNotUnsetPropertyAccess() {
    $conf = $this->getConfig(['a' => 'foo']);
    unset($conf->a);
    $this->assertFalse($conf->offsetExists('a'));
  }

  public function testCanNotUnsetArrayAccess() {
    $conf = $this->getConfig(['a' => 'foo']);
    unset($conf['a']);
    $this->assertFalse($conf->offsetExists('a'));
  }

  # ToArray
  public function testToArray() {
    $data = [
        'john' => [
            'name' => 'John',
            'age'  => 50
        ],
        'jane' => [
            'name' => 'Jane',
            'age'  => 40
        ],
    ];
    $conf = $this->getConfig($data);
    $this->assertEquals($data, $conf->toArray());
  }


  # Path
  public function testGetByPathReturnsNullWhenNotSet() {
    $conf = $this->getConfig();
    $this->assertNull($conf->path('name'));
  }

  public function testGetByPathWithDefaultWhenNotSet() {
    $conf = $this->getConfig();
    $this->assertEquals(50, $conf->path('age', 50));
  }

  public function testGetByPath_separatedByDotIsDefault() {
    $conf = $this->getConfig([
        'foo' => 'bar',
        'database' => [
            'host' => 'localhost',
            'charset'  => [
                'primary' => 'utf8'
            ],
        ]
    ]);
    $this->assertEquals('bar', $conf->path('foo'));
    $this->assertEquals('localhost', $conf->path('database.host'));
    $this->assertEquals('utf8', $conf->path('database.charset.primary'));
  }

  public function testGetByPath_SeparatedWithCustomDelimiter() {
    $conf = $this->getConfig([
        'foo' => 'bar',
        'database' => [
            'host' => 'localhost',
            'charset'  => [
                'primary' => 'utf8'
            ],
        ]
    ]);
    $this->assertEquals('utf8', $conf->path('database.charset.primary'));
    $this->assertEquals('utf8', $conf->path('database-charset-primary', null,'-'));
    $this->assertEquals('utf8', $conf->path('database/charset/primary', null,'/'));
  }


  # Iterate
  public function testCanIterate() {
    $data = [
        'a' => 'foo',
        'b' => 'bar',
        'c' => 'baz'
    ];
    $conf = $this->getConfig($data);

    $i = 0;
    foreach ($conf as $k => $v) {
      $this->assertArrayHasKey($k, $data);
      $this->assertEquals($data[$k], $v);
      $i++;
    }
    $this->assertEquals(count($data), $i);
  }
  
  # Lockable
  public function testIsLockable() {
    $conf = $this->getConfig(['a' => 'foo']);
    $conf->lock();
    $this->assertTrue($conf->isLocked());
  }

  # helpers

  /**
   * @param array $data
   * @return Config|\PHPUnit_Framework_MockObject_MockObject
   */
  private function getConfig(array $data = []) {
    // php <  7.0
    return $this->getMockForAbstractClass(Config::class, [$data]);

    // php >= 7.0
    // return new class($data) extends Config {}; // php >= 7.0
  }
}