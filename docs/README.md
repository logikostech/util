## Logikos\Util\Config
Partial port of [Phalcon\Config][phalcon-docs-config]

### Differences from Phalcon\Config
#### `path` method not implemented
```php
$conf = new \Phalcon\Config([
  'database' => [
    'host' => 'localhost'
  ]
]);

$host = $conf->path('database.host') // 'localhost'
```
#### Better numeric indexing on merge
Because of `merge()` we must deal with numeric indexes.  The difference can best be seen in these 2 unit tests
```php
  public function testMergeWithSomeNumericIndexes() {
    $a = new \Logikos\Util\Config([
        0     => 'John',
        'age' => 50
    ]);
    $b = new \Logikos\Util\Config([
        0     => 'Jane',
        'age' => 40
    ]);
    $a->merge($b);
    
    $this->assertEquals(
        [
            0     => 'John',
            'age' => 40,
            1     => 'Jane'
        ],
        $a->toArray()
    );
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
    
    $this->assertEquals(
        [
            0     => 'John',
            'age' => 40,
            2     => 'Jane'
        ],
        $a->toArray()
    );
  }
```
Notice in Util\Config the index for Jane is 1, vs 2 in the Phalcon\Config version


[phalcon-docs-config]: https://docs.phalconphp.com/en/3.2/Phalcon_Config