## Logikos\Util

Welcome to the Logikos Util documentation.

- [Abstract Config]
  - [Mutable Config] - adds `set($key, $value)` and `merge(Config $config)`
  - [Immutable Config] - adds `with($key, $value)` and throws `OutOfBoundsException` on assignment after construction
  - [Strict Config] - type safe config with validation.
  
[Config]: ../src/Config.php
[MutableConfig]: ../src/Config/MutableConfig.php
[ImmutableConfig]: ../src/Config/ImmutableConfig.php
[Phalcon\Config]: https://docs.phalconphp.com/en/3.2/Phalcon_Config
[Abstract Config]: config/README.md
[Mutable Config]: config/mutable.md
[Immutable Config]: config/immutable.md
[Strict Config]: config/strict.md