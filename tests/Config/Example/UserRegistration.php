<?php

namespace Logikos\Util\Tests\Config\Example;

class UserRegistration {

  public function register(UserData $config) {
    $config->validate();
  }
}