# Logikos\Util\Config\ImmutableConfig
- Derivative of [Abstract Config]
- [ImmutableConfig Source][ImmutableConfig]
- Immutable (obviously)
- [General Usage](#general-usage)
- [`with` Method](#with)

Immutable Config works the same as the [Abstract Config] except any attempt to mutate results in a thrown `CanNotMutateException` and it adds a `with($key, $value)` [method](#with).

## General Usage
```php
    $config = new ImmutableConfig([
        "env"      => "production",
        "database" => [
            "adapter"  => "Mysql",
            "host"     => "localhost"
        ]
    ]);
    
    # export as array
    $config->toArray();
    
    # Set value
    $config->foo   = 'bar';                  // throws \Logikos\Util\CanNotMutateException
    $config['foo'] = 'bar';                  // throws \Logikos\Util\CanNotMutateException
    $config->offsetSet('foo', 'bar');        // throws \Logikos\Util\CanNotMutateException
    
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
```

## With
```php
    use Logikos\Util\Config\ImmutableConfig;
    
    # With
    $conf1 = new ImmutableConfig(["name" => "John"]);
    $conf2 = $conf1->with('age', 40);
    
    $conf1->toArray();                       // ['name'=>'John']
    $conf2->toArray();                       // ['name'=>'John', 'age'=>40]
```

[Config]: ../../src/Config.php
[MutableConfig]: ../../src/Config/MutableConfig.php
[ImmutableConfig]: ../../src/Config/ImmutableConfig.php
[Phalcon\Config]: https://docs.phalconphp.com/en/3.2/Phalcon_Config
[Abstract Config]: README.md