<?php

namespace Logikos\Util\Config;

class OptionNotDefinedException extends ConfigException {
  const DEFAULT_MESSAGE = 'You tried to set an option which does not exist.';
}