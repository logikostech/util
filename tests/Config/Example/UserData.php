<?php

namespace Logikos\Util\Tests\Config\Example;

use Logikos\Util\Config\Field;
use Logikos\Util\Validation\Validator;
use Logikos\Util\Config\StrictConfig;

/**
 * @property string    $first_name
 * @property string    $email
 * @property string    $reg_date
 * @property integer   $age
 * @property \DateTime $birthday
 * @property string    $referrer
 */
class UserData extends StrictConfig {
  public function initialize() {
    $this->addFields(
        $this->firstNameField(),
        $this->emailField(),
        $this->registrationDateField(),
        $this->ageField(),
        $this->birthdayField(),
        $this->howDidYouHearAboutUsField()
    );
  }

  private function firstNameField() {
    return Field\Field::withValidators(
        'first_name',
        new Validator\Regex(
            '/^[a-zA-Z0-9_]+$/',
            'May contain only letters, numbers, and underscore'
        ),
        new Validator\Regex(
            '/^.{3,30}$/',
            'Length must be between 3 and 30 chars long'
        )
    );
  }

  private function emailField() {
    return Field\Field::withValidators(
        'email',
        new Validator\Callback(
            function ($email) {
              return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
            },
            'Must be a valid email address'
        )
    );
  }

  private function registrationDateField() {
    $field = Field\Adapter\Particle::required('reg_date');
    $field->chain()->datetime('Y-m-d');
    return $field;
  }

  private function ageField() {
    return Field\OptionalField::withValidators(
        'age',
        new Validator\Callback(
            'is_int',
            'Must be a valid integer'
        )
    );
  }

  private function birthdayField() {
    return Field\OptionalField::withValidators(
        'birthday',
        new Validator\IsInstanceOf(\DateTime::class)
    );
  }

  private function howDidYouHearAboutUsField() {
    return new Field\OptionalField('referrer');
  }
}