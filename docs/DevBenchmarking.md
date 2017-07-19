# Development and Benchmarking

The purpose of this library is to be fast. If you have to cut corners for speed, do it. Theres no fines for going too fast here!

### Built-in Benchmarking

The library provides a simple way to track the time usage between multiple calls, this is done via a static Benchmark call in the code:

```php
Benchmark::start(__METHOD__,__LINE__);

// ... Your code ...

Benchmark::finish(__METHOD__,__LINE__);
```

The benchmark times the start and finish for a specific method, so try to avoid adding multiple benchmarks for a single method; split them up!

##### Example output

When run via the CLI the output will look something like:

```
> $ php tests/cli.php character

Duration: 0000000000   + 0000000000       Mem: 2526328          Line 53    in  Lodestone\Parser\Character\Parser::parse
Duration: 0.00011200   + 0.00011000       Mem: 2527496          Line 26    in  Lodestone\Parser\Character\TraitProfile::parseProfile
Duration: 0.00018400   + 0000000000       Mem: 2528320          Line 48    in  Lodestone\Parser\Character\TraitProfile::parseProfileBasic
Duration: 0.00241900   + 0.00223500  !    Mem: 2531528          Line 75    in  Lodestone\Parser\Character\TraitProfile::parseProfileBasic
Duration: 0.00252500   + 0.00010600       Mem: 2530424          Line 83    in  Lodestone\Parser\Character\TraitProfile::parseProfileBiography
Duration: 0.00462400   + 0.00209900  !    Mem: 2531320          Line 93    in  Lodestone\Parser\Character\TraitProfile::parseProfileBiography
```

Most of this should be obvious;

- All timestamps are in milliseconds
- The first timestamp is the total duration over the course of the entire script
- The second timestamp is the total time it took to complete the task from Start to Finish, so in this example we can see that `parseProfileBasic` took `0.00223500` to complete. The `!` flag is because it took over `0.002` which is our target. It is okay to hover around this range.
- The next number, is the current Peak memory usage. The main factor is to keep this consistent without huge spikes.

##### Custom logging

If you don't want to use the Benchmark you can just grab a timestamp from it to use in a log, eg:

```php
$started = Benchmark::milliseconds();

// ... your code ...

$finished = Benchmark::milliseconds();
Logger::write(__CLASS__, __LINE__, sprintf('PARSE DURATION: %s ms', $finished - $started));
```

This can be useful for timing multiple bits of code in a single method


##### Timing foreach loops

You might have realised that timing multiple times in a method is annoying, so how about foreach? There is a third param for `Benchmark::start/finish` for this specific case.

```php
$method = sprintf('%s(%s)', __METHOD__, $name);
Benchmark::start($method,__LINE__);
```

By providing a third parameter you can represent the method to include the parameters passed. For example this could be a string or an increment value.



