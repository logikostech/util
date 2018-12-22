<?php

namespace LogikosTest\Util\Validation\Validator;

use Logikos\Util\Validation\Validator;

class IsIterableTest extends TestCase {
  public function testHasDefaultDescription() {
    $v = new Validator\IsIterable();
    $this->assertNotEmpty($v->getDescription());
  }

  public function testSetAndGetDescription() {
    $sut = new Validator\IsIterable('some desc');
    $this->assertSame('some desc', $sut->getDescription());
  }

  public function testArrayIsIterable() {
    $sut = new Validator\IsIterable();
    $this->assertIsValid($sut, ['foo','bar']);
  }

  public function testTraversable() {
    $sut = new Validator\IsIterable();
    $traversable = new class implements \IteratorAggregate {
      public $prop1 = 'foo';
      public $prop2 = 'bar';
      public function getIterator() {
        return new \ArrayIterator($this);
      }
    };
    $this->assertIsValid($sut, $traversable);
  }

  public function testArrayObject() {
    $this->assertIsValid(
        new Validator\IsIterable(),
        new \ArrayObject(['foo','bar'])
    );
  }

  public function testNotIterable() {
    $sut = new Validator\IsIterable();
    foreach ($this->notIterableValues() as $value)
      $this->assertInvalid($sut, $value, var_export($value, true).' should not be iterable but is');
  }

  private function notIterableValues() {
    return [
        'string',
        1, // int
        new \stdClass(), // generic object
        null,
        false
    ];
  }
}