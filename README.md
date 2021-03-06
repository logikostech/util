[![Travis CI](https://img.shields.io/travis/logikostech/util/master.svg)](https://travis-ci.org/logikostech/util)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/logikostech/util/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/logikostech/util/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/logikostech/util/badge.svg?branch=master)](https://coveralls.io/github/logikostech/util?branch=master)
[![CodeCov](https://codecov.io/gh/logikostech/util/branch/master/graph/badge.svg)](https://codecov.io/gh/logikostech/util)
[![Minimum PHP Version](https://img.shields.io/packagist/php-v/logikos/util.svg)](https://php.net/)
[![Software License](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/logikostech/util/master/LICENSE)

# Logikos\Util
Utility classes

- [Installation](#installation)
  - [Composer](#composer)
  - [Github](#github)
- [Documentation](#documentation)

## Installation

### Composer

Install Composer in a common location or in your project:
```bash
curl -s http://getcomposer.org/installer | php
```

Require library:
```bash
composer.phar require logikos/util
```

### GitHub
Just clone the repository in a common location or inside your project:
```bash
git clone https://github.com/logikostech/util.git
```

## Documentation
- [Abstract Config] - base config object largely inspired by Phalcon\Config
  - [Mutable Config] - adds `set($key, $value)` and `merge(Config $config)`
  - [Immutable Config] - adds `with($key, $value)` and throws `OutOfBoundsException` on assignment after construction
  - [Strict Config] - type safe config with validation.
    
[Config]: src/Config.php
[MutableConfig]: src/Config/MutableConfig.php
[ImmutableConfig]: src/Config/ImmutableConfig.php
[Phalcon\Config]: https://docs.phalconphp.com/en/3.2/Phalcon_Config
[Abstract Config]: docs/config/README.md
[Mutable Config]: docs/config/mutable.md
[Immutable Config]: docs/config/immutable.md
[Strict Config]: docs/config/strict.md