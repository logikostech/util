<?php

namespace Logikos\Util\Validation;

interface Validator {
  public function validate($value) : bool;
  public function getDescription();
}