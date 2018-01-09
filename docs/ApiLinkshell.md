# Linkshell

### API Pages:
- [API: Characters](docs/ApiCharacters.md)
- [API: Database](docs/ApiDatabase.md)
- [API: Forums](docs/ApiForums.md)
- [API: FreeCompany](docs/ApiFreeCompany.md)
- [API: Internals](docs/ApiInternals.md)
- [API: Linkshell](docs/ApiLinkshell.md)
- [API: Lodestone](docs/ApiLodestone.md)

### searchLinkshell( string $name, ...[string $server], [$page = false])
Returns `array`

Search for a specific linkshell. Server and page are both optional. Page should be a number if set, otherwise it defaults to page 1. Lodestone currently displays 50 linkshells per page with a maximum of 1,000.


### getLinkshellMembers( int $id )
Returns: `array`

Get a list of members in the specified linkshell.
