<?php

namespace Logikos\Util;

class CanNotMutateException extends \Exception {
  const DEFAULT_MESSAGE = 'Modification is prohibited.';

  public function __construct($message = "", $code = 0, \Throwable $previous = null) {
    parent::__construct(
        $message ? $message : self::DEFAULT_MESSAGE,
        $code,
        $previous
    );
  }
}