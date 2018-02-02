<?php

namespace Logikos\Util\Tests\Config\Example;

use Logikos\Util\Tests\Config\TestCase;
use DateTime;

class UserDataTest extends TestCase {
  public function testInstantiate() {
    $config = new UserData();
    $this->assertFalse($config->isValid());
  }
  public function testValid() {
    $config = new UserData();
    $config->first_name = 'fred';
    $config->email      = 'fred@foobar.com';
    $config->reg_date   = (new DateTime('now'))->format('Y-m-d');
    $config->age        = 30;
    $config->birthday   = new DateTime('Jan 1st 1988');
    $config->validate();
    $this->assertTrue($config->isValid());
  }
}