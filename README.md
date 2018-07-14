# Final Fantasy XIV: Lodestone PHP Parser
This project is a maintained PHP library for parsing data directly from the FFXIV Lodestone website.

The goal is to provide an extremely fast and lightweight library, it is built with the purpose of parsing as many characters as possible, key being: Low memory, and micro-timed parsing methods.

> If you would like more data, consider the [XIVDB Rest API](https://github.com/xivdb/api).

## Notes

- This library parses the live Lodestone website. This website is based in Tokyo.
- This library is built in PHP 7.1 minimum, please use the latest as this can increase 

## Getting Started

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
- [Travis CI](https://travis-ci.org/Simbiat/lodestone-php/branches)

## Settings
It's possible to set your own UserAgent used by CURL: simply use `->setUseragent('useragent')`
It's also possible to change LodeStone language by `->setLanguage('na')`. Accepted langauge values are `na`, `eu`, `jp`, `fr`, `de`

## Contributing

- [Read the contributing guide first!](CONTRIBUTING.md)
- Switch to `dev` when working on the repo please!

**If you contribute to this library, please add your github user here :)**

- [@shentschel](https://github.com/shentschel)
- [@Simbiat](https://github.com/Simbiat)

