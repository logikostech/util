<?php
namespace Logikos\Util\Config\Option;

interface Validator {
  public function validate($value);
}