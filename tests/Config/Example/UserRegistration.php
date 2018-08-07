<?php

namespace LogikosTest\Util\Config\Example;

class UserRegistration {
  public function register(UserData $config) {
    $config->validate();
    // do stuff
  }
}