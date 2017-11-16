# Logikos\Util\Config
Abstract [Config] is a partial port of [Phalcon\Config] and serves as a base class for [MutableConfig] and [ImmutableConfig] though you could derive your own classes from it also.

## Derivitives
- [Mutable Config] - adds `set($key, $value)` and `merge(Config $config)`
- [Immutable Config] - adds `with($key, $value)` and throws `OutOfBoundsException` on assignment after construction 

[Config]: ../../src/Config.php
[MutableConfig]: ../../src/Config/MutableConfig.php
[ImmutableConfig]: ../../src/Config/ImmutableConfig.php
[Phalcon\Config]: https://docs.phalconphp.com/en/3.2/Phalcon_Config
[Mutable Config]: mutable.md
[Immutable Config]: immutable.md