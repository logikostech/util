<?php

namespace Logikos\Util\Tests\Config;

use Logikos\Util\Config;

class StrictConfigBuilderTest extends TestCase {
  /** @var  Config\StrictConfigBuilder */
  private $sut;

  public function setUp() {
    $this->sut = new Config\StrictConfigBuilder();
  }

  Public function testBuildReturnsStrictConfig() {
    $this->assertInstanceOf(
        Config\StrictConfig::class,
        $this->sut->build()
    );
  }

  public function testCanAddOption() {
    $this->markTestSkipped('WIP');
    $this->sut->addOption(new Config\Option('name'));

  }
}