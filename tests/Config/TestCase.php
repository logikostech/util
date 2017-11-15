<?php

namespace Logikos\Util\Tests\Config;

use Logikos\Util\Config;

class TestCase extends \PHPUnit\Framework\TestCase {

  protected function assertConfValue(Config $conf, $key, $value) {
    $this->assertKeyExists($conf, $key);
    $this->assertEquals($value, $conf->offsetGet($key));
  }

  protected function assertKeyExists(Config $conf, $offset) {
    $this->assertTrue($conf->offsetExists($offset));
  }

  protected function assertKeyNotExists(Config $conf, $offset) {
    $this->assertFalse($conf->offsetExists($offset));
  }
}