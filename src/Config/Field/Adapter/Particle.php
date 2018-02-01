<?php

namespace Logikos\Util\Config\Field\Adapter;

use Logikos\Util\Config\Field;
use Particle\Validator\ValidationResult;


class Particle implements Field {
  /**
   * @var Particle\Validator
   */
  private $validator;
  private $name;
  /**
   * @var Particle\Chain
   */
  private $chain;

  protected function __construct($name, Particle\Validator $validator, Particle\Chain $chain) {
    $this->validator = $validator;
    $this->name = $name;
    $this->chain = $chain;
  }

  public static function required($name) : Particle {
    $v = new Particle\Validator();;
    return new self($name, $v, $v->required($name));
  }

  public function getName() {
    return $this->name;
  }

  public function validate($value): Field\Validation\Result {
    $particleResult = $this->validator->validate([
        $this->name => $value
    ]);
    if ($particleResult->isValid())
      return new Field\Validation\ValidResult();

    return $this->invalidResult($particleResult);
  }

  private function invalidResult(ValidationResult $particleResult) {
    $messages = [];
    foreach ($particleResult->getFailures() as $failure) {
      array_push($messages, $failure->format());
    }
    return new Field\Validation\InvalidResult($messages);
  }

  public function chain() {
    return $this->chain;
  }
}