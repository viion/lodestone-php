

## Commands

The easiest way to start is by using the quick API class:

```php
$api = new \Lodestone\Api;
```

With this you can call:
- `getLog()`
- `searchCharacter(name, server, [page])`
- `searchFreeCompany(name, server, [page])`
- `searchLinkshell(name, server, [page])`

- `getCharacter(id)`
- `getCharacterFriends(id)`
- `getCharacterFollowing(id)`
- `getCharacterAchievements(id)`

- `getFreeCompany(id)`
- `getFreeCompanyMembers(id)`

- `getLinkshellMembers(id)`

- `getLodestoneBanners()`
- `getLodestoneNews()`
- `getLodestoneTopics()`
- `getLodestoneNotices()`
- `getLodestoneMaintenance()`
- `getLodestoneUpdates()`
- `getLodestoneStatus()`

- `getWorldStatus()`
- `getDevBlog()`
- `getDevPosts()`
- `getFeast()`
- `getDeepDungeon()`

To clear XIVDB cache:

```php
$api = new \Lodestone\Api;
$api->xivdb->clearCache();
$api->xivdb->init();
```

All objects can be converted to an array via: `toArray()`

```php
$api = new \Lodestone\Api;
$character = $api->getCharacter('<id>');
$array = $character->toArray();
```

To generate a consistent sha1:

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

### Provides parsing

- Charcters
- Characters friends
- Characters followers
- Characters achievements
- Character search
- Free company
- Free company search
- Free company members
- Linkshell
- Linkshell search
- News
- Topics
- Notices
- Maintenance
- Status
- Feast (All seasons)
- Deep Dungeon
- World Status
- Dev Blog (the PR one)
- Dev Track posts


WIP:
- Grand company ranking
- Database pages


## Getting setup

The easiest way to get started is by using composer:

```shell
composer require viion/lodestone-php
```

```php
$api = new \Lodestone\Api;
// api...
```

If you are not familiar with composer, you can download this repository and you will need `symfony/css` repository as well. You would need to build your own auto-loader for this!


### Examples
View the `tests/cli.php` for examples.

The repo comes with a basic vagrant file for testing.

You can also view `tests/run.php` (wip)


### basic usage

The project comes with a very quick API class

```php
$api = new \Lodestone\Api;
$character = $api->getCharacter(1234);
```

The API doesn't do any guess work with requests, that means if you do not have the ID for a character you must search for it first. This is intentional.

```php
$api = new \Lodestone\Api;

// search for characters
$characters = $api->searchCharacter('name', 'server');

// loop through characters
foreach($characters['results'] as $char) {
    $char = (Object)$char;
    $char = (Object)$api->getCharacter($char->id);
    print_r($char->name);
}
```


### Generating a hash

```php
$character = $api->getCharacter(1234, true);
```
