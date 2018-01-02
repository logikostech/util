<?php

namespace Logikos\Util\Tests\Config\Example;

use Logikos\Util\Config\Option\NonRequiredOption;
use Logikos\Util\Config\Option\RequiredOption;
use Logikos\Util\Config\Option\Validator;
use Logikos\Util\Config\StrictConfig;

class RegisterUsecaseConfig extends StrictConfig {
  public function onConstruct() {
    $this->addOptions(
        $this->firstNameOption(),
        $this->emailOption(),
        $this->howDidYouHearAboutUsOption()
    );
  }

  private function firstNameOption() {
    return RequiredOption::withValidators(
        'first_name',
        new Validator\PatternMatch(
            '/[a-zA-Z]/',
            'May contain only upper and lower case letters'
        ),
        new Validator\PatternMatch(
            '/[a-zA-Z]/',
            'Must be between 2 and 30 characters long'
        )
    );
  }

  private function emailOption() {
    return RequiredOption::withValidators(
        'email',
        new Validator\Custom(
            function ($email) { return filter_var($email, FILTER_VALIDATE_EMAIL) !== false; },
            'Invalid email address'
        )
    );
  }

  private function howDidYouHearAboutUsOption() {
    return new NonRequiredOption('referrer');
  }
}