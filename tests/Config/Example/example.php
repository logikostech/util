<?php

class StrictConfig extends Config {
  public function validate() {

  }
}

class UserData extends StrictConfig {
  public function onConstruct() {
    $this->addFields(
        $this->usernameField(),
        $this->emailField(),
        $this->ageField()
    );
  }

  private function usernameField() {
    $field = new RequiredField('username');
    $field->addPattern(
        '/^[a-zA-Z0-9_]+$/',
        'May contain only letters, numbers, and underscore'
    );
    $field->addPattern(
        '/^.{3,20}$/',
        'Length must be between 3 and 30 chars long'
    );
    return $field;
  }

  private function emailField() {
    $field = new RequiredField('email');
    $field->addCallable(
        function($value){ return filter_var($value, FILTER_VALIDATE_EMAIL); },
        'Must be a valid email address'
    );
    return $field;
  }

  private function ageField() {
    $field = new Field('age');
    $field->addCallable('is_int', 'Must be a valid integer');
    return $field;
  }
}

class AddUserUsecase {
  public function __construct(UserData $userdata) {
    $userData->validate();

  }
}