<?php

namespace Logikos\Util\Config\Option;


use Logikos\Util\Config\ConfigException;

class InvalidOptionValueException extends ConfigException {
  const DEFAULT_MESSAGE = 'The value you tried to set is not a valid value for this option.';
}