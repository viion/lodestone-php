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

# Linkshell

### searchPvPTeam( string $name, ...[string $server], [$page = false])
Returns `array`

Search for a specific PvPTeam. Server and page are both optional. Page should be a number if set, otherwise it defaults to page 1. Lodestone currently displays 50 PvPTeams per page with a maximum of 1,000.


### getPvPTeam( int $id )
Returns: `PvPTeam` Model

Returns page with members. Default one is first page. Reverts to 1st page, if requsting a page, which does not exist
