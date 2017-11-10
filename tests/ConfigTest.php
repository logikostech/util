<?php

namespace Logikos\Util\Tests;

use PHPUnit\Framework\TestCase;
use Logikos\Util\Config;

class ConfigTest extends TestCase {

  public function testInitWithNothingCreatesEmptyConfig() {
    $conf = new Config();
    $this->assertEquals(0, $conf->count());
    $this->assertEquals(0, count($conf));
  }

  public function testWhenEmpty_CountIsZero() {
    $conf = new Config([]);
    $this->assertEquals(0, count($conf));
  }

  public function testWhenOneItemInArray_CountIsOne() {
    $conf = new Config(['foo' => 'bar']);
    $this->assertEquals(1, count($conf));
  }

  public function testCanGetValues() {
    $conf = new Config(
        [
            'name' => 'John',
            'age'  => 50
        ]
    );
    $this->assertEquals('John', $conf->get('name'));
    $this->assertEquals(50, $conf->get('age'));
  }

  public function testWhenKeyNotSet_GetReturnsNull() {
    $conf = new Config(['name' => 'John', 'age' => 50]);
    $this->assertTrue(is_null($conf->get('phone')));
  }

  public function testCanSpecifyDefaultValueWithGet() {
    $conf = new Config(['name' => 'John', 'age' => 50]);
    $this->assertEquals(
        '123-456-7890',
        $conf->get('phone', '123-456-7890')
    );
  }

  public function testHasKey() {
    $conf = new Config(['name' => 'John', 'age' => 50]);
    $this->assertTrue($conf->has('name'));
    $this->assertFalse($conf->has('phone'));
  }

  public function testCanAccessAsProperty() {
    $conf = new Config(['name' => 'John']);
    $this->assertEquals('John', $conf->name);
  }

  public function testOffsetGet() {
    $conf = new Config(['name' => 'John']);
    $this->assertEquals('John', $conf['name']);
  }

  public function testOffsetSet() {
    $conf = new Config(['name' => 'John']);
    $conf['age'] = 50;
    $this->assertEquals(50, $conf['age']);
  }

  public function testOffsetExists() {
    $conf = new Config(['name' => 'John']);
    $conf['age'] = 50;
    $this->assertTrue(isset($conf['age']));
    $this->assertFalse(isset($conf['birthdate']));
  }

  public function testOffsetUnset() {
    $conf = new Config(['name' => 'John', 'age' => 50]);
    unset($conf['name']);
    $this->assertFalse(isset($conf['name']));
    $this->assertFalse($conf->has('name'));
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
    $conf = new Config($data);
    $this->assertSame($data['john'], $conf['john']);
  }

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
    $conf = new Config($data);
    $this->assertEquals($data, $conf->toArray());
  }

  public function testInnerArraysBecomeConfigObjects() {
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
    $conf = new Config($data);
    $this->assertTrue($conf['john'] instanceof Config);
  }

  public function testMerge() {
    $a = new Config(['name' => 'John']);
    $b = new Config(['age' => 50]);
    $a->merge($b);
    $this->assertEquals(
        ['name' => 'John', 'age' => 50],
        $a->toArray()
    );
  }

  public function testMergeWithNumericIndex() {
    $a = new Config(
        [
            0 => 'John',
            1 => 'Fred'
        ]
    );
    $b = new Config(
        [
            0 => 'Bill',
            1 => 'Greg'
        ]
    );
    $a->merge($b);
    $this->assertEquals(
        [
            'John',
            'Fred',
            'Bill',
            'Greg'
        ],
        $a->toArray()
    );
  }

  public function testMergeWithSomeNumericIndexes() {
    $a = new Config([
        0     => 'John',
        'age' => 50
    ]);
    $b = new Config([
        0     => 'Jane',
        'age' => 40
    ]);
    $a->merge($b);
    $this->assertEquals(
        [
            0     => 'John',
            'age' => 40,
            1     => 'Jane'
        ],
        $a->toArray()
    );
  }

  public function testDeepMerge() {
    $a = new Config([
        'database' => [
            'host' => 'localhost',
            'user' => 'root'
        ]
    ]);
    $b = new Config([
        'database' => [
            'host' => '192.168.0.111',
            'port' => 12345
        ]
    ]);
    $a->merge($b);
    $this->assertEquals(
        [
            'database' => [
                'host' => '192.168.0.111',
                'user' => 'root',
                'port' => 12345
            ]
        ],
        $a->toArray()
    );
  }

  public function testAssignmentOfArrayIsConfigObject() {
    $conf = new Config();
    $conf->john   = ['name'=> 'John', 'age'=> 50];
    $conf['jane'] = ['name'=> 'Jane', 'age'=> 40];
    $this->assertTrue($conf['john'] instanceof Config);
    $this->assertTrue($conf['jane'] instanceof Config);
  }

  public function testMergeComplexObjects() {

    $config1 = new Config([
        'controllersDir' => '../x/y/z',
        'modelsDir'      => '../x/y/z',
        'database'       => [
            'adapter'  => 'Mysql',
            'host'     => 'localhost',
            'username' => 'scott',
            'password' => 'cheetah',
            'name'     => 'test_db',
            'charset'  => [
                'primary' => 'utf8'
            ],
            'alternatives' => [
                'primary' => 'latin1',
                'second'  => 'latin1'
            ]
        ],
    ]);
    $config2 = new Config([
        'modelsDir' => '../x/y/z',
        'database'  => [
            'adapter'  => 'Postgresql',
            'host'     => 'localhost',
            'username' => 'peter',
            'options'  => [
                'case' => 'lower',
                \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            ],
            'alternatives' => [
                'primary' => 'swedish',
                'third'   => 'american',
            ],
        ],
    ]);
    $config1->merge($config2);
    $expected = Config::__set_state([
        'controllersDir' => '../x/y/z',
        'modelsDir'      => '../x/y/z',
        'database' => Config::__set_state([
            'adapter'  => 'Postgresql',
            'host'     => 'localhost',
            'username' => 'peter',
            'password' => 'cheetah',
            'name'     => 'test_db',
            'charset'  => Config::__set_state([
                'primary' => 'utf8',
            ]),
            'alternatives' => Config::__set_state([
                'primary' => 'swedish',
                'second'  => 'latin1',
                'third'   => 'american',
            ]),
            'options' => Config::__set_state([
                'case' => 'lower',
                (string) \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            ]),
        ]),
    ]);
    $this->assertEquals($expected, $config1);
  }

  public function testNestedConfigMergeWithNumericIndex() {
    $config = new Config([
        0 => [1]
    ]);
    $config->merge(new Config([
        0 => [2]
    ]));
    $expected = [
        0 => [1, 2]
    ];

    $this->assertEquals($expected, $config->toArray());
  }

  public function testGetByPathReturnsNullWhenNotSet() {
    $conf = new Config([]);
    $this->assertNull($conf->path('name'));
  }

  public function testGetByPathWithDefaultWhenNotSet() {
    $conf = new Config([]);
    $this->assertEquals(50, $conf->path('age', 50));
  }

  public function testGetByPath_separatedByDotIsDefault() {
    $conf = new Config([
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
    $conf = new Config([
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
}