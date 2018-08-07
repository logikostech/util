<?php

namespace Logikos\Util\Config;

use Logikos\Util\Validation;

interface Field {
  public function getName();
  public function validate($value): Validation\Result;
}