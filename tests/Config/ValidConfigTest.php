<?php

namespace Logikos\Util\Tests\Config;


use Logikos\Util\Config;

class ValidConfigTest extends TestCase {
  public function testImplementsConfig() {
    $this->assertInstanceOf(Config::class, new Config\ValidConfig());
  }

}