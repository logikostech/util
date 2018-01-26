<?php

namespace Logikos\Util\Config;

interface Field {
  public function getName();
  public function validate($value): Field\ValidationResult;
}