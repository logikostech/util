<?php

namespace Logikos\Util\Config\Field;

interface ValidationResult {
  public function isValid(): bool;
  public function getMessages();
  public function getValue();
}