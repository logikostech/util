<?php

namespace Logikos\Util\Config;

interface Option {
  public function getName();
  public function validationMessages($value);
  public function isValidValue($value);
}