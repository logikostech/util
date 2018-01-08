<?php

namespace Logikos\Util\Config;

class InvalidConfigStateException extends \RuntimeException {

  /** @var StrictConfig */
  private $config;

  public function __construct(StrictConfig $config) {
    $this->config = $config;
    parent::__construct($this->getMessagesAsYmlString());
  }

  public function getMessagesAsYmlString() {
    $string = '';
    foreach ($this->config->validationMessages() as $optionName => $messages) {
      $string .= sprintf(
          "\n%s:\n- %s",
          $optionName,
          implode("\n- ", $messages)
      );
    }
    return trim($string);
  }

  public function getValidationMessages() {
    return $this->config->validationMessages();
  }

  public function getConfig() { return $this->config; }
}