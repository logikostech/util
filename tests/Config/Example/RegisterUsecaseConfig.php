<?php

namespace Logikos\Util\Tests\Config\Example;

use Logikos\Util\Config\Field;
use Logikos\Util\Config\Field\Validation\Validator;
use Logikos\Util\Config\StrictConfig;

class RegisterUsecaseConfig extends StrictConfig {
  public function initialize() {
    $this->addFields(
        $this->firstNameField(),
        $this->emailField(),
        $this->howDidYouHearAboutUsOption()
    );
  }

  private function firstNameField() {
    return Field\Field::withValidators(
        'first_name',
        new Validator\Regex(
            '/[a-zA-Z]/',
            'May contain only upper and lower case letters'
        ),
        new Validator\Regex(
            '/[a-zA-Z]/',
            'Must be between 2 and 30 characters long'
        )
    );
  }

  private function emailField() {
    return Field\Field::withValidators(
        'email',
        new Validator\Callback(
            function ($email) { return filter_var($email, FILTER_VALIDATE_EMAIL) !== false; },
            'Invalid email address'
        )
    );
  }

  private function howDidYouHearAboutUsOption() {
    return new Field\OptionalField('referrer');
  }
}