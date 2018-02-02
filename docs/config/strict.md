# Config\StrictConfig

This is an abstract class which you are supposed to extend.

- Derivative of [Abstract Config]
- [StrictConfig Source][StrictConfig]
- [Example Usage](#example-usage)

## Example Usage
use the `initialize()` method to define fields with the `addFields` method

The @property annotations are just for the IDE so that $config->email auto completes for example and so that it knows that $config->birthday is a DateTime object.

```php
use Logikos\Util\Config\Field;
use Logikos\Util\Config\Field\Validation\Validator;
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
```
Notice you can use `Logikos\Util\Config\Field\Field` or you can use the `Logikos\Util\Config\Field\Field\Adapter\Particle` which allows you to use [Particle\Validator] for the fields.  Or you could build your own Fields or field adapters to use, they just have to implement the `Logikos\Util\Config\Field` interface.

The entire point of being able to do the above is so you can do this:
```php
class UserRegistration {
  public function register(UserData $config) {
    $config->validate();
    // do stuff
  }
}
```

Now you do not need setters for every field.  You are able to seperate your data transfer objects and your validation logic.

[Config]: ../../src/Config.php
[MutableConfig]: ../../src/Config/MutableConfig.php
[ImmutableConfig]: ../../src/Config/ImmutableConfig.php
[StrictConfig]: ../../src/Config/StrictConfig.php
[Phalcon\Config]: https://docs.phalconphp.com/en/3.2/Phalcon_Config
[Abstract Config]: README.md
[Particle\Validator]: https://packagist.org/packages/particle/validator
