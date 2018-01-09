# Getting Started

### Install via composer:

```php
composer require viion/lodestone-php
```

If you are not familiar with composer, you can download this repository and you will need symfony/css repository as well. You would need to build your own auto-loader for this!

### Tests

- You can run the php unit tests: `phpunit`
- You can also run the API Checker: `php tests/run.php`
- And you can run the debugger: `php tests/cli.php <type> <id>`
    - Type can be: `character`, `fc`, `ls`, etc. View the file for details

### Usage

Methods are called against the API class, for example:

```php
$api = new Lodestone\Api();
$character = $api->getCharacter(<id>);
```


### Quick example:

```php
$api = new \Lodestone\Api;

// search for characters
$characters = $api->searchCharacter('name', 'server');

// loop through characters
foreach($characters['results'] as $character) {
    $character = $api->getCharacter($character['id']);
    
    // print character name
    print_r($character->getCharacter());
}
```

### Misc notes:


##### toArray()

All objects can be converted to an array via: `toArray()`

```php
$api = new \Lodestone\Api;
$character = $api->getCharacter('<id>');
$array = $character->toArray();
```

##### Sha1 Hash

To generate a consistent data sha1 for comparison purposes:

```php
$api = new \Lodestone\Api;
$character = $api->getCharacter('<id>');
$hash = $character->getHash();
```

To ensure an accurate sha1, data that is out of control of the player is removed from the hash calculation, for characters this includes:

- avatar/portrait: urls can change
- Most icons: urls can change
- free company: being kicked from an fc would change hash
- biography: se may decide to adjust this or how the HTML is formatted
- stats: stat formula's do change per class.


##### Exceptions

There are special exceptions for handling page not found (eg: character deleted) or lodestone being down for maintenance:

```php
try {
  ... api call ...
} catch (HttpMaintenanceValidationException $hmvex) {
  ... lodestone down for maintenance ...
} catch (HttpNotFoundValidationException $hnfvex) {
  ... page not found / deleted ...
} catch (ValidationException $vex) {
  ... do something with it ...
}
```

Read more about exceptions and validation in: [Validation](/docs/Validation.md)
