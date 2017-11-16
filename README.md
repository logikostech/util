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

#### [Mutable](src/Config/MutableConfig.php) Examples
```php
$config = new \Logikos\Util\Config\MutableConfig([
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
$config['database']['dbname'] = 'something';
$config->set('env', 'Development');
$config->offsetSet('env', 'Development');

# Get value
$value = $config->database->host;
$value = $config['database']['host'];
$value = $config->get('database')->get('host');
$value = $config->offsetGet('database')->offsetGet('host');

# Get value that does not exist
$value = $config->get('foo');            // null
$value = $config->get('foo', 'default'); // 'default'
$value = $config->foo;                   // throws OutOfBoundsException
$value = $config['foo'];                 // throws OutOfBoundsException

# Check if the key exists
$exists = isset($config->something);
$exists = isset($config['something']);
$exists = $config->has('something');
$exists = $config->offsetExists('something');

# Unset
unset($config->something);
unset($config['something']);
$config->offsetUnset('something');

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

#### [Immutable](src/Config/ImmutableConfig.php) Examples
Immutable Config works the same way except trying to set or merge results in a thrown `CanNotMutateException`

It does have an extra with method though.
```php
# With
$conf1 = new \Logikos\Util\Config\ImmutableConfig([
    "name" => "John"
]);
$conf2 = $conf1->with('age', 40);

$conf1->toArray(); // ['name'=>'John']
$conf2->toArray(); // ['name'=>'John', 'age'=>40]

# Set
$conf1->set('foo', 'bar'); // FATAL ERROR: Call to undefined method Config::set()
$conf1->foo   = 'bar';     // throws \Logikos\Util\CanNotMutateException
$conf1['foo'] = 'bar';     // throws \Logikos\Util\CanNotMutateException

# Merge
$conf1->merge([]);         // FATAL ERROR: Call to undefined method Config::set()
```