<?php

namespace Logikos\Util\Config\Field\Validation;

interface Result {
  const VALID   = true;
  const INVALID = false;

  public function isValid(): bool;
  public function getMessages();
}