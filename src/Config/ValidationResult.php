<?php


namespace Logikos\Util\Config;

use Logikos\Util\Validation\Result;

class ValidationResult {

  /** @var Result[] */
  private $fieldResults = [];

  public function addResult($fieldName, Result $fieldResult) {
    $this->fieldResults[$fieldName] = $fieldResult;
  }

  public function result($fieldName) {
    return $this->fieldResults[$fieldName] ?? null;
  }

  public function getMessages() {
    $response = [];
    foreach ($this->fieldResults as $fieldName => $result) {
      $msgs = $result->getMessages();
      if (!empty($msgs))
        $response[$fieldName] = $msgs;
    }
    return $response;
  }

  public function isValid() {
    foreach ($this->fieldResults as $result)
      if (!$result->isValid()) return Result::INVALID;
    return Result::VALID;
  }
}