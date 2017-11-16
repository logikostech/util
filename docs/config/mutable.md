# Logikos\Util\Config\MutableConfig
- Derivative of [Abstract Config]
- [MutableConfig Source][MutableConfig]
- [Differences from Phalcon\Config](#phalcon-config)
- [Examples](#examples)


## Examples
```php
    $config = new \Logikos\Util\Config\MutableConfig([
        "env"      => "production",
        "database" => [
            "adapter"  => "Mysql",
            "host"     => "localhost"
        ]
    ]);
    
    # export as array
    $config->toArray();
    
    # Set value
    $config->env = 'development';
    $config['env'] = 'development';
    $config->set('env', 'development');
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
    
    # Merge
    $config->merge(new \Logikos\Util\Config\MutableConfig([
        "database" => [
            "host" => "192.168.0.203",
            "port" => 3307
        ],
        "foo" => "bar"
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
    public function testMergeWithSomeNumericIndexes() {
        $a = new \Logikos\Util\Config\MutableConfig([
            0     => 'John',
            'age' => 50
        ]);
        $b = new \Logikos\Util\Config\MutableConfig([
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
        $a = new \Phalcon\Config([
            0     => 'John',
            'age' => 50
        ]);
        $b = new \Phalcon\Config([
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