# Lodestone

### API Pages:
- [API: Characters](/docs/ApiCharacters.md)
- [API: Database](/docs/ApiDatabase.md)
- [API: Forums](/docs/ApiForums.md)
- [API: FreeCompany](/docs/ApiFreeCompany.md)
- [API: Internals](/docs/ApiInternals.md)
- [API: Linkshell](/docs/ApiLinkshell.md)
- [API: Lodestone](/docs/ApiLodestone.md)

### getLodestoneBanners()
Returns `array`

Get the current lodestone banners.

### getLodestoneNews()
Returns `array`

Get the current lodestone news.

### getLodestoneTopics()
Returns `array`

Get the current lodestone topics.

### getLodestoneNotices()
Returns `array`

Get the current lodestone notices.

### getLodestoneMaintenance()
Returns `array`

Get the current maintenance posts.

### getLodestoneUpdates()
Returns `array`

Get the current update posts.

### getLodestoneStatus()
Returns `array`

Get the current game status posts.

### getWorldStatus()
Returns `array`

Get the world status page. 

> Note that this page is not available when the game is under maintenance (ironic... i know)

----

The below are pages directly linked from the Lodestone but are independent.

### getDevBlog()
Returns `array`

Get the current dev blogs.

### getFeast(...[$season = false], [$params = []])
Returns `array`

Get the current feast information. Season can be provided to view older feasts. Params would be based on the current live site (there are many to mention and they're complicated)


### getDeepDungeon(...[$params = []])
Returns `array`

Get the current deep dungeon information. Params would be based on the current live site (there are many to mention and they're complicated)
