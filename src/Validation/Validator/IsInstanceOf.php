<?php

namespace Logikos\Util\Validation\Validator;

use Logikos\Util\Validation\Validator;

class IsInstanceOf implements Validator {
  private $fqcn;
  private $description;

  /**
   * @param string $fqcn
   * @param string $description
   * @throws Exception
   */
  public function __construct($fqcn, $description = null) {
    if (!$this->isValidClassName($fqcn))
      throw new Exception("No such class: {$fqcn}");

    $this->fqcn = $fqcn;
    $this->description = $description ?? "Must be an instance of {$fqcn}";
  }

  public function getFqcn() {
    return $this->fqcn;
  }

  public function validate($value) : bool {
    return is_a($value, $this->fqcn);
  }

  protected function isValidClassName($fqcn) {
    return class_exists($fqcn) || interface_exists($fqcn);
  }

  public function getDescription() {
    return $this->description;
  }
}