<?php

namespace LogikosTest\Util\Config\Field\Adapter\Particle;

use Logikos\Util\Config\Field\Adapter\Particle\Chain;
use Logikos\Util\Config\Field\Adapter\Particle\Validator;
use LogikosTest\Util\Config\TestCase;

class ValidatorTest extends TestCase {
  public function testRequireReturnsLogikosChain() {
    $this->assertInstanceOf(Chain::class, (new Validator())->required('foo'));
  }

  public function testOptionalReturnsLogikosChain() {
    $this->assertInstanceOf(Chain::class, (new Validator())->optional('foo'));
  }
}