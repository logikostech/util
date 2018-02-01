<?php

namespace Logikos\Util\Tests\Config\Field\Adapter\Particle;

use Logikos\Util\Config\Field\Adapter\Particle\Chain;
use Logikos\Util\Config\Field\Adapter\Particle\Validator;
use Logikos\Util\Tests\Config\TestCase;

class ValidatorTest extends TestCase {
  public function testRequireReturnsLogikosChain() {
    $this->assertInstanceOf(Chain::class, (new Validator())->required('foo'));
  }

  public function testOptionalReturnsLogikosChain() {
    $this->assertInstanceOf(Chain::class, (new Validator())->optional('foo'));
  }
}