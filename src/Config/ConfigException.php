<?php

namespace Logikos\Util\Config;

use Throwable;

class ConfigException extends \Exception {
  const DEFAULT_MESSAGE = 'Configuration Exception';

  public function __construct($message = "", $code = 0, Throwable $previous = null) {
    parent::__construct(
        $this->_message($message),
        $code,
        $previous
    );
  }

  private function _message($message) {
    return empty($message)
        ? static::DEFAULT_MESSAGE
        : $message;
  }
}