<?php

namespace Logikos\Util\Tests\Config;

use Logikos\Util\Config\MutableConfig;
use Logikos\Util\Config\PathEval;

class PathEvalTest extends TestCase {
  public function testPath() {
    $conf = new MutableConfig([
        'a' =>'foo',
        'b' => [
            'z' => 'bar',
            'y' => [
                'c' => 'baz'
            ]
        ]
    ]);
    $sut = new PathEval($conf);
    $this->assertEquals('foo', $sut->find('a'));
    $this->assertEquals('bar', $sut->find('b.z'));
    $this->assertEquals('baz', $sut->find('b.y.c'));
  }
}