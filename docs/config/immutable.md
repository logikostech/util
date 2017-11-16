# [Logikos\Util\Config\MutableConfig][MutableConfig]
- Derivative of [Abstract Config]
- Immutable (obviously)

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
    $conf1->set('foo', 'bar');       // FATAL ERROR: Call to undefined method Config::set()
    $conf1->foo   = 'bar';           // throws \Logikos\Util\CanNotMutateException
    $conf1['foo'] = 'bar';           // throws \Logikos\Util\CanNotMutateException
    $conf1->offsetSet('foo', 'bar'); // throws \Logikos\Util\CanNotMutateException
    
    # Merge
    $conf1->merge([]);               // FATAL ERROR: Call to undefined method Config::set()
```

[Config]: ../../src/Config.php
[MutableConfig]: ../../src/Config/MutableConfig.php
[ImmutableConfig]: ../../src/Config/ImmutableConfig.php
[Phalcon\Config]: https://docs.phalconphp.com/en/3.2/Phalcon_Config
[Abstract Config]: README.md