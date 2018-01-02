<?php

namespace Logikos\Util\Tests\Config\Example;


use Logikos\Util\Config\InvalidConfigStateException;

class RegesterUsecase {
  public function __construct(RegisterUsecaseConfig $config) {
    if (!$config->isValid())
      throw new InvalidConfigStateException($config);
  }
}