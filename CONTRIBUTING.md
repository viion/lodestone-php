# How to Contribute

This is only a guideline how to contribute to this project. It shall also describe a way for all contributors to review code.

## Feature Branches

A good way to implement features is to create a branch for each feature. This allows focused work on a feature without implementing to much other stuff into it.

When a feature is finished it will be merged into the `dev` branch, once the `dev` branch as a whole becomes stable everything will get merged into `master`. Feature branch will be deleted afterwards.

<b>TLDR;</b> [feature branch and submit the result as a pull request](https://lefedt.de/blog/posts/2013/contributing-to-oss-through-pull-requests/)

## Commit Message Conventions

Be specific in your commit messages, don't group a bunch of changes into a single commit and message it "Stuff". Do very tight-small commits with a message explaining what the change/addition does even if it may seem obvious from the change itself, on the history we only see the message!

The commit message is what is what describes your contribution. Its purpose must therefore be to document what a commit contributes to a project.

Its head line __should__ be as meaningful as possible because it is always seen along with other commit messages.

Its body __should__ provide information to comprehend the commit for people who care.

Its footer __may__ contain references to external artifacts (issues it solves, related commits) as well as breaking change notes. This applies to __all kind of projects__.


### Format

#### Short form (only subject line)
```
    <type>(<scope>): <subject>
```

#### Long form (with body)
```
    <type>(<scope>): <subject>
    <BLANK LINE>
    <body>
    <BLANK LINE>
    <footer>
```

First line cannot be longer than __70 characters__, second line is always blank and other lines should be wrapped at __80 characters__! This makes the message easier to read on github as well as in various git tools.

#### Allowed <type>

 * feat (feature)
 * fix (bug fix)
 * docs (documentation)
 * style (formatting, missing semi colons, …)
 * refactor
 * test (when adding missing tests)
 * chore (maintain)
 * improve (improvement, e.g. enhanced feature)
 
example: `chore(Validator): refactor isNotHttperror`
example: `feat(docs): add contributing guidelines`

#### Breaking changes

All breaking changes have to be mentioned in footer with the description of the change, justification and migration notes

    BREAKING CHANGE: Id editing feature temporarily removed
        As a work around, change the id in XML using replace all or friends

#### Referencing issues

Closed bugs / feature requests / issues should be listed on a separate line in the footer prefixed with "Closes" keyword like this:

    Closes #234

or in case of multiple issues:

    Closes #123, #245, #992

### More on good commit Messages

 * http://365git.tumblr.com/post/3308646748/writing-git-commit-messages
 * http://tbaggery.com/2008/04/19/a-note-about-git-commit-messages.html

## Code Style Guidelines

### Braces

- Please use 4 spaces, no tabs
- Please follow PSR [1](http://www.php-fig.org/psr/psr-1/) and [2](http://www.php-fig.org/psr/psr-2/)

Braces for a new class or function should be on a new line

Bad:
```php
public myClass {
    public myFunction() {
        ...
    }
}

Good:
```php
public myClass
{
    public myFunction()
    {
    ....
    }
}
```

For loop, switch and if constructs braces should be in the same line

Bad:
```php
if (done)
{
    ...
}
else
{
    ...
}
```

Good:
```php
if (done) {
    ...
} else {
    ...
}

### Spaces

All indentations should have a width of 4 spaces. Please use spaces instead of
tabs to indent your code!

For readability there should also be a space after a loop, switch or if construct

Bad:
```php
if(done) {}
```

Good:
```php
if (done) {}
```

### Comments

Comments should only have a declarative function. Mostly used for PSR stuff. Instead of comments you should try to write self-describing code. If you use PHP Storm, let it auto write your docblocks!

Bad:

```php
/**
 * A function which calculates the sum of two values
 */
public calculateSum($value1, $value2)
{
    // take value1 and add it to value2
    $value2 += $value1;

    // return sum
    return $value2;
}
```

Good:
```php
/**
 * Sum Calculation
 *
 * @param int $value1
 * @param int $value2
 * @return int sum
 */
public myFunction($value1, $value2)
{
    return $value1 + $value2;
}
```

For more Informations about clean code have a look into Robert C. Martin's book
[Clean Code](http://ricardogeek.com/docs/clean_code.html)
