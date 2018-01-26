<?php

namespace Logikos\Util\Config\Field\Validation\Validator;

use Logikos\Util\Config\Field\Validation\Validator;

class Regex implements Validator {
  private $pattern;
  private $message;

  /**
   * @param string $pattern
   * @param string $description
   * @throws Exception
   */
  public function __construct($pattern, $description = null) {
    if (!$this->isValidRegexPattern($pattern))
      throw new Exception();
    $this->pattern = $pattern;
    $this->message = $description;
  }

  public function getPattern() {
    return $this->pattern;
  }

  public function validate($value) {
    return preg_match($this->pattern, $value) !== 0;
  }

  protected function isValidRegexPattern($pattern) {
    return @preg_match($pattern, null) !== false;
  }

  public function getDescription() {
    return $this->message;
  }
}