# Config\MutableConfig

Mutable Config works the same as the [Abstract Config] with some additional methods, it adds `set($key, $value)` and `merge(Config $config)`

- Derivative of [Abstract Config]
- [MutableConfig Source][MutableConfig]
- [General Usage](#general-usage)
  - [Merge](#merge)
- [Differences from Phalcon\Config](#phalcon-config)

## General Usage
Most of this is the same as the [Abstract Config Example]
```php
    use Logikos\Util\Config\MutableConfig as Config;
    
    $config = new Config([
        "env"      => "production",
        "database" => [
            "adapter"  => "Mysql",
            "host"     => "localhost"
        ]
    ]);
    
    # export as array
    $config->toArray();
    
    # Set value
    $config->env   = 'development';
    $config['env'] = 'development';
    $config->set('env', 'development');      // added method
    $config->offsetSet('env', 'development');
    
    # Set nested values
    $config->database->dbname     = 'something';
    $config['database']['dbname'] = 'something';
    $config->database->set('dbname', 'something');
    $config->database->offsetSet('dbname', 'something');
    $config->get('database')->set('dbname', 'something');
    
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

### Merge
```php
    use Logikos\Util\Config\MutableConfig as Config;
    
    $config = new Config([
        "env"      => "production",
        "database" => [
            "adapter"  => "Mysql",
            "host"     => "localhost"
        ]
    ]);
    $config->merge(new Config([
        "database" => [
            "host"     => "192.168.0.203",
            "port"     => 3307
        ],
        "foo"      => "bar"
    ]));
    $config->env;               // production
    $config->database->adapter; // mysql
    $config->database->host;    // 192.168.0.203
    $config->database->port;    // 3307
    $config->foo;               // bar
```


## Phalcon Config
[MutableConfig] is a partial port of [Phalcon\Config].  Although this class is similar to phalcon's config object, there are a few differences.

### Exceptions for undefined property access
In [Phalcon\Config] it allows php to trigger whatever normal errors would occure for trying to access a class property that does not exist.
[MutableConfig] however throws OutOfBoundsException.  For example:
```php
    $conf = new \Logikos\Util\Config\MutableConfig(['a'=>'foo']);
    $b = $conf['b'];  // throws OutOfBoundsException
    $b = $conf->b;    // throws OutOfBoundsException
```

### Better numeric indexing on merge
Because of `merge()` we must deal with numeric indexes.  The difference can best be seen in these 2 unit tests. Notice in Util\Config the index for Jane is 1, vs 2 in the Phalcon\Config version.
```php
    use Logikos\Util\Config\MutableConfig;
    use Phalcon\Config as PhalconConfig;
    
    public function testMergeWithSomeNumericIndexes() {
        $a = new MutableConfig([
            0     => 'John',
            'age' => 50
        ]);
        $b = new MutableConfig([
            0     => 'Jane',
            'age' => 40
        ]);
        
        $a->merge($b);
        
        $expected = [
            0     => 'John',
            'age' => 40,
            1     => 'Jane'
        ];
        
        $this->assertEquals($expected , $a->toArray());
    }

    public function testPhalconMergeWithSomeNumericIndexes() {
        $a = new PhalconConfig([
            0     => 'John',
            'age' => 50
        ]);
        $b = new PhalconConfig([
            0     => 'Jane',
            'age' => 40
        ]);
        
        $a->merge($b);
        
        $expected = [
            0     => 'John',
            'age' => 40,
            2     => 'Jane'
        ];
        
        $this->assertEquals($expected, $a->toArray());
    }
```

[Config]: ../../src/Config.php
[MutableConfig]: ../../src/Config/MutableConfig.php
[ImmutableConfig]: ../../src/Config/ImmutableConfig.php
[Phalcon\Config]: https://docs.phalconphp.com/en/3.2/Phalcon_Config
[Abstract Config]: README.md
[Abstract Config Example]: README.md#examples