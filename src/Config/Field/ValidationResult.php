<?php

namespace Logikos\Util\Config\Field;

interface ValidationResult {
  const VALID   = true;
  const INVALID = false;

  public function isValid(): bool;
  public function getMessages();
}