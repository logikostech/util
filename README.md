[![Travis CI](https://img.shields.io/travis/logikostech/util/master.svg)](https://travis-ci.org/logikostech/util)
[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%205.6-8892BF.svg)](https://php.net/)
[![Software License](https://img.shields.io/badge/license-MIT-blue.svg)](https://raw.githubusercontent.com/logikostech/class-options/master/LICENSE)

# Logikos\Util
Utility classes

## Installation

### Installing via Composer

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

### Installing via GitHub

Just clone the repository in a common location or inside your project:

```bash
git clone https://github.com/logikostech/util.git
```

## Highlights

### [Logikos\Util\Config](src/Config.php)
Largely inspired by [Phalcon\Config](https://docs.phalconphp.com/hr/3.2/api/Phalcon_Config), it converts an array into a config object.  Each nested array also becomes a config object.

#### [Mutable](src/Config/MutableConfig.php) 

#### [Immutable](src/Config/ImmutableConfig.php) Examples
