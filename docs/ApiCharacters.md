# Characters

Methods are called against the API class:

> $api = new Lodestone\Api();

## Methods

```php
getCharacter( int $id )
```
Gets information for a character.

```php
getCharacterFriends( int $id )
```
Gets a list of characters who are friends of `$id`.

```php
getCharacterFollowing( int $id )
```
Gets a list of characters who `$id` is following.

```php
getCharacterAchievements( int $id, int $kind = 1)
```
Get a list of achievements for `$id` for a specified `$kind`, Kind is the category the achievements are under, this would be either:

- 1 = Battle
- 2 = Character
- 4 = Items
- 5 = Crafting
- 6 = Gathering
- 8 = Quests
- 11 = Exploration
- 12 = Grand Company
- 13 = Legacy (May not exist)

```php
getCharacter( int $id )
```
Gets information for a character

```php
getCharacter( int $id )
```
Gets information for a character
