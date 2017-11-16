# Logikos\Util\Config
Abstract [Config] is a partial port of [Phalcon\Config] and serves as a base class for [MutableConfig] and [ImmutableConfig] though you could derive your own classes from it also.

The primary purpose of this class is to convert an array into a configuration object.  Each nested array also becomes a config object.

## Derivitives
- [Mutable Config] - adds `set($key, $value)` and `merge(Config $config)`
- [Immutable Config] - adds `with($key, $value)` and throws `OutOfBoundsException` on assignment after construction 


## Examples
```php
    $config = new class() extends \Logikos\Util\Config([
        "env"      => "production",
        "database" => [
            "adapter"  => "Mysql",
            "host"     => "localhost",
            "username" => "scott",
            "password" => "cheetah"
        ]
    ]);
    
    # export as array
    $config->toArray();
    
    # Set value
    $config->env = 'development';
    $config['env'] = 'development';
    $config->offsetSet('env', 'development');
    
    # Set nested values
    $config->database->dbname     = 'something';
    $config['database']['dbname'] = 'something';
    $config->database->offsetSet('dbname', 'something');
    $config->get('database')->offsetSet('dbname', 'something');
    
    # Get value
    $value = $config->env;
    $value = $config['env'];
    $value = $config->get('env');
    $value = $config->offsetGet('env');
    
    # Get nested values
    $value = $config->database->host;
    $value = $config['database']['host'];
    $value = $config->get('database')->get('host');
    $value = $config->offsetGet('database')->offsetGet('host');
    
    # Get value that does not exist
    $value = $config->foo;                   // throws OutOfBoundsException
    $value = $config['foo'];                 // throws OutOfBoundsException
    $value = $config->get('foo');            // null
    $value = $config->get('foo', 'default'); // 'default'
    
    # Check if the key exists
    $exists = $config->has('something');
    $exists = isset($config->something);
    $exists = isset($config['something']);
    $exists = $config->offsetExists('something');
    
    # Unset
    unset($config->something);
    unset($config['something']);
    $config->offsetUnset('something');
```

[Config]: ../../src/Config.php
[MutableConfig]: ../../src/Config/MutableConfig.php
[ImmutableConfig]: ../../src/Config/ImmutableConfig.php
[Phalcon\Config]: https://docs.phalconphp.com/en/3.2/Phalcon_Config
[Mutable Config]: mutable.md
[Immutable Config]: immutable.md