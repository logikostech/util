<?php

namespace Logikos\Util\Config\Option\Validator;


use Logikos\Util\Config\Option\Validator;

class PatternMatch implements Validator {
  private $pattern;
  private $message;

  /**
   * @param string $pattern
   * @param string $message
   * @throws ValidatorException
   */
  public function __construct($pattern, $message = null) {
    if (!$this->isValidRegexPattern($pattern))
      throw new ValidatorException();
    $this->pattern = $pattern;
    $this->message = $message;
  }

  public function getPattern() {
    return $this->pattern;
  }

  public function validate($value) {
    return preg_match($this->pattern, $value) !== 0;
  }

  private function isValidRegexPattern($pattern) {
    return @preg_match($pattern, '') !== FALSE;
  }

  public function getMessage() {
    return $this->message;
  }
}