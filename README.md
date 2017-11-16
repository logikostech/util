[![Travis CI](https://img.shields.io/travis/logikostech/util/master.svg)](https://travis-ci.org/logikostech/util)
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
### [Logikos\Util\Config](src/config.php)
Largely inspired by [Phalcon\Config](https://docs.phalconphp.com/hr/3.2/api/Phalcon_Config), it converts an array into a config object.  Each nested array also becomes a config object.
#### Examples
```php
$config = new \Logikos\Util\Config([
    "database" => [
        "adapter"  => "Mysql",
        "host"     => "localhost",
        "username" => "scott",
        "password" => "cheetah"
    ]
]);

# export as array
$config->toArray();

# Set/alter value
$config->database->dbname     = 'something';
// or
$config['database']['dbname'] = 'something';

# Get value
$value = $config->database->host;
// or
$value = $config['database']['host'];
// or
$value = $config->get('database')->get('host');

# Check if the key exists
$exists = isset($config->something);
// or
$exists = isset($config['something']);
// or
$exists = $config->has('something');

# Unset
unset($config->something);
// or
unset($config['something']);

# Merge
$config->merge(new Config([
    "database" => [
        "host" => "192.168.0.203",
        "port" => 3307
    ],
    "foo" => "bar"
]));
$config->database->adapter; // mysql
$config->database->host;    // 192.168.0.203
$config->database->port;    // 3307
$config->foo;               // bar
```