# Final Fantasy XIV: Lodestone PHP Parser
This project is PHP library for parsing data directly from the FFXIV Lodestone website based on one develoepd by [@viion](https://github.com/viion).

The goal is to provide an extremely fast and lightweight library, it is built with the purpose of parsing as many characters as possible, key being: Low memory, and micro-timed parsing methods.

## Notes
- This library parses the live Lodestone website. This website is based in Tokyo.
- This library is built in PHP 7.1 minimum, please use the latest as this can increase 

## What's different?
If you are using the original library there is not a "need" to switch, but this update already has some differences, that may get you interested:
    1. It has different code structure, that aims at reduction of rarely used or unnecessary functions and some standartization (on-going attempt). Tests show that in some cases it can provide slightly faster results winning ~10ms of time. If you are using it heavily like I am doing on [FFTracker](https://simbiat.ru/fftracker) you will be greatful even for such small boost. This also implies some code cleaning and, potentially, retirement of objects in some cases to get additional boost.
    2. Using regex instead of full HTML parsing for extra speed, where possible.
    3. More filters for your queries. This is mainly for search subfunctions, but other calls also may already or will have additional options to provide more flexibility.
    4. Return more potential useful information in searches.
    5. Attempt at multilangual support. Search functions already support multilingual filters (insterad of IDs) and at the moment, it looks like only some character parser functions need to be updated to provide full support.


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

## Settings
It's possible to set your own UserAgent used by CURL: simply use `->setUseragent('useragent')`
It's also possible to change LodeStone language by `->setLanguage('na')`. Accepted langauge values are `na`, `eu`, `jp`, `fr`, `de`
