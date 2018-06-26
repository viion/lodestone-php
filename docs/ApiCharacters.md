- [Getting Started](docs/GettingStarted.md)  
  - [API: Characters](docs/ApiCharacters.md)  
  - [API: Database](docs/ApiDatabase.md)  
  - [API: Forums](docs/ApiForums.md)  
  - [API: FreeCompany](docs/ApiFreeCompany.md)  
  - [API: Internals](docs/ApiInternals.md)  
  - [API: Linkshell](docs/ApiLinkshell.md)  
  - [API: PvPTeam](/docs/ApiPvPTeam.md)  
  - [API: Lodestone](docs/ApiLodestone.md)  
- [Entities](docs/Entities.md)  
- [Validation](docs/Validation.md)  
- [DOM Library](docs/DomLibraryLegacy.md) (Third-Party)  
- [Travis CI](https://travis-ci.org/viion/lodestone-php/branches)

---

# Characters
### searchCharacter( string $name, ...[string $server], [$page = false])  
Returns `array`  
  
Search for a specific character. Server and page are both optional. Page should be a number if set, otherwise it defaults to page 1. Lodestone currently displays 50 characters per page with a maximum of 1,000.  
  
### getCharacter( int $id )  
Returns: `CharacterProfile` Model  
  
Gets information for a character.  
  
### getCharacterFriends( int $id )  
Returns: `array`  
  
Gets a list of characters who are friends of `$id`.  
  
### getCharacterFollowing( int $id )  
Returns: `array`  
  
Gets a list of characters who `$id` is following.  
  
### getCharacterAchievements( int $id, int $kind = 1, bool $includeUnobtained = false, $category = false, bool $details = false, $achid = false)  
Returns: `array`  
  
Get a list of achievements for `$id` for a specified `$kind`, Kind is the category the achievements are under.  
> Note: Fetching all achievement pages can take a significant amount of time  
> Note: Some achievements do not show, until obtained or until marked as visible on Lodestone. Some unobtainable (eg mutually exclusive) are always shown on Lodestone as well, but are skipped by parser.  
- 1 = Battle  
- 2 = Character  
- 4 = Items  
- 5 = Crafting  
- 6 = Gathering  
- 8 = Quests  
- 11 = Exploration  
- 12 = Grand Company  
- 13 = Legacy (only characters from before Realm Reborn (2.0) update can have this)

| Param | Default | Details |
| --- | --- | --- |
| `$id` | - | Which character achievements to parse |
| `$kind` | 1 | The achievement category kind, SE named this, not us :) |
| `includeUnobtained` | `false` | If to include unobtained achievements in the list |
| `$category` | `false` | The achievement sub-category to parse |
| `$details` | `false` | Parse the achievement details including rewards, items, titles etc. This will impact performance. |
| `$achid` | `false` | If `$details` is set to true, this will reduce the details down to a specific ID |

```php  
$achievements = [];  
foreach([1,2,4,5,6,8,11,12,13] as $kind) {  
	$achievements[$kind] = $api->getCharacterAchievements(<id>, $kind);
}  
```
