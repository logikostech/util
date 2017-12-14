<?php

namespace Logikos\Util\Config\Option;

use Logikos\Util\Config\ConfigException;

class InvalidOptionNameException extends ConfigException {
  const DEFAULT_MESSAGE = 'Invalid Option Name';
}