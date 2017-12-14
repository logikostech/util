<?php

namespace Logikos\Util\Tests\Config;

use Logikos\Util\Config\StrictConfigBuilder;
use Logikos\Util\StrictConfig;

class StrictConfigBuilderTest extends TestCase {
  /** @var  StrictConfigBuilder */
  private $sut;

  public function setUp() {
    $this->sut = new StrictConfigBuilder();
  }

  Public function testBuildReturnsStrictConfig() {
    $this->assertInstanceOf(
        StrictConfig::class,
        $this->sut->build()
    );
  }
}