<?php

namespace Logikos\Util\Config\Field\Validation;

interface Validator {
  public function validate($value) : bool;
  public function getDescription();
}