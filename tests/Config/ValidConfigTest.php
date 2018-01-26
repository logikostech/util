<?php

namespace Logikos\Util\Tests\Config;


use Logikos\Util\Config;

class ValidConfigTest extends TestCase {
  public function testImplementsConfig() {
    $sut = new class extends Config\ValidConfig {
      protected function initialize() {}
    };
    $this->assertInstanceOf(Config::class, $sut);
  }

  protected function validConfig() {
    new class extends Config\ValidConfig {
      protected function initialize() {

      }
    };
  }
}