<?php

namespace LogikosTest\Util\Config;

use Logikos\Util\Config;
use PHPUnit\Framework\AssertionFailedError;

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

  protected function assertExceptionWillThrow($type, callable $callable) {
    try {
      $callable();
      $this->fail("Failed to assert that {$type} would be thrown");
    }
    catch(AssertionFailedError $e) {
      throw $e; // we are just excluding this type from the catch bellow ...
    }
    catch (\Exception $e) {
      $this->assertInstanceOf(
          $type,
          $e,
          "Expected Exception: {$type} \n Actual: ".get_class($e)
      );
    }
  }
}