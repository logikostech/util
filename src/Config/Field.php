<?php

namespace Logikos\Util\Config;

interface Field {
  public function validate($value): Field\ValidationResult;
}