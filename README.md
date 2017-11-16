[![Travis CI](https://img.shields.io/travis/logikostech/util/master.svg)](https://travis-ci.org/logikostech/util)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.6-8892BF.svg)](https://php.net/)
[![Software License](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/logikostech/class-options/master/LICENSE)

# Logikos\Util
Utility classes

- [Installation](#installation)
  - [Composer](#composer)
  - [Composer](#github)
- [Documentation](#documentation)

## Installation

### Composer

Install Composer in a common location or in your project:

```bash
curl -s http://getcomposer.org/installer | php
```

create or edit the `composer.json` file as follows:

```json
    {
        "repositories": [
            {
                "type": "git",
                "url": "https://github.com/logikostech/util"
            }
        ],
        "require": {
            "logikostech/uril": "dev-master"
        }
    }
```

Run the composer installer:

```bash
    $ php composer.phar install
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
  
[Config]: src/Config.php
[MutableConfig]: src/Config/MutableConfig.php
[ImmutableConfig]: src/Config/ImmutableConfig.php
[Phalcon\Config]: https://docs.phalconphp.com/en/3.2/Phalcon_Config
[Abstract Config]: docs/config/README.md
[Mutable Config]: docs/config/mutable.md
[Immutable Config]: docs/config/immutable.md
