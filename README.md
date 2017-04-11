# lodestone-php

[![Latest Stable Version](https://poser.pugx.org/viion/lodestone-php/v/stable)](https://packagist.org/packages/viion/lodestone-php)
[![Total Downloads](https://poser.pugx.org/viion/lodestone-php/downloads)](https://packagist.org/packages/viion/lodestone-php)
[![Latest Unstable Version](https://poser.pugx.org/viion/lodestone-php/v/unstable)](https://packagist.org/packages/viion/lodestone-php)
[![License](https://poser.pugx.org/viion/lodestone-php/license)](https://packagist.org/packages/viion/lodestone-php)

Small lodestone parser built in PHP. The goals are to be **extremely fast** and very lightweight memory, a single character parse should be under 3mb memory. This is built for parsing thousands of characters a minute.

On the very first parse, if XIVDB data is required it will download it. It will then be cached in an `xivdb.json` file. You can run:

```php
$xivdb = new Lodestone\Modules\XIVDB();
$xivdb->clearCache();
```

Provides parsing:
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
