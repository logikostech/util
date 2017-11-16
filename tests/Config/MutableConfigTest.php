<?php

namespace Logikos\Util\Tests\Config;

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


  # Merge
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
}