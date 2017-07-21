# Entities

The parser is broken up into multiple entities for different purposes, for example a character would contain a `CharacterProfile` which contains a `Grand Company` and an array of `Collectables`.

This document explains how it is setup and how future implementations should work.

## Structure

### Parser

Parser is structured like so:

- `Lodestone\Parser\< Subject >\Parser.php`

Subject examples: `Character`, `CharacterFriends`, `FreeCompany`, `Lodestone`, think of these as pages on lodestone.

Different sections of the parser will be broken up into traits. For characters this would be:

- `TraitAttributes`, parses the character attributes
- `TraitProfile`, parses the character profile information
- `TraitGear`, Parses the characters equipped gear

*etc*

### Entity

During parse, you would populate an entity. Entities should be single instances for example `Grand Company`. If there are multiple entities, eg: `Collectables` (Minions/Mounts), then this would be an array of `Collectable` objects assigned to a parent (eg: `Profile`)


