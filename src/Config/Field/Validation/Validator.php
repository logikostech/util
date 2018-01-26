<?php

namespace Logikos\Util\Config\Field\Validation;

interface Validator {
  public function validate($value);
  public function getDescription();
}