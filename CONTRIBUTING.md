# How to Contribute

This is only a guideline how to contribute to this project.
It shall also describe a way for all contributors to review
code.

## Feature Branches

A good way to implement features is to create a branch for each feature.
This allows a focused work on a feature without implementing to much other
stuff into it.

When a feature is finished it will be merged into the master branch and the
feature branch will be deleted afterwards.

<b>TLDR;</b> [feature branch and submit the result as a pull request](https://lefedt.de/blog/posts/2013/contributing-to-oss-through-pull-requests/)

### Pro

A feature branch
* allows to be assigned to a single issue
* focuses on a single feature
* can easily be reviewed because it is focused
* GitHub allows to create pull requests for branches
* you can force push changes to a feature branch without breaking stuff
* It allows a clean structure

### Cons

* needs a good management of all contributors
* there is some work in merging stuff when many people work on a project

## Squash and Rebase

A good way to provide a clean commit history is to squash multiple commits
of a feature branch into a single commit. Then it can be rebased into master.
Rebase provides a way to merge stuff without the annoing `Merge ...` messages

An Example:

You have 2 branches. Branch 1 is `master` and branch 2 is `feature-a`
in `feature-a` you have two commits like:

* feat(debug): implement debugger (commit-hash: aaaa)
* fix(debug): remove unused variables (commit-hash: bbbb)

Now you can do a `git rebase -i HEAD~2` which rebases all commits until
HEAD - last 2 commits. This will produce the following output:

```
pick aaaa feat(debug): implement debugger
pick bbbb fix(debug): remove unused variables
```

now we can change the second `pick` into one of the following attributes:

* reowrd - use commit, but edit the commit message
* edit - use commit, but stop for ammending
* squash - use commit, but meld into prevoius commit
* fixup - like `sqash`, but discards this commit's message
* exec - run a shell command
* drop - remove the commit

In our example we use `fixup`because it is a fix:

```
pick aaaa feat(debug): implement debugger
fixup bbbb fix(debug): remove unused variables
```

After we finished that process `git log` only shows 1 commit with commit-hash `aaaa`.
Now we can switch to our master branch and enter the following git command:

`git cherry-pick aaaa`

This will insert the commit with hash `aaaa` into our master branch.

When you need to merge more than one commit, execute the follwing  commands:

```
git checkout feature-a
git rebase master
git checkout master
git merge feature-a
```

with this you can provide a clean commit history.

## Commit Message Conventions

The contents of this page are partly based on the [angular commit messages document](https://docs.google.com/document/d/1QrDFcIiPjSLDn3EL15IJygNPiHORgU1_OOAqWjiDU5Y/edit?pli=1).
We would prefer this conventions to create a common style for our commit messages
## Purpose

The commit message is what is what describes your contribution.
Its purpose must therefore be to document what a commit contributes to a project.

Its head line __should__ be as meaningful as possible because it is always
seen along with other commit messages.

Its body __should__ provide information to comprehend the commit for people
who care.

Its footer __may__ contain references to external artifacts
(issues it solves, related commits) as well as breaking change notes.

This applies to __all kind of projects__.


### Format

#### Short form (only subject line)

    <type>(<scope>): <subject>

#### Long form (with body)

    <type>(<scope>): <subject>

    <BLANK LINE>

    <body>

    <BLANK LINE>

    <footer>

First line cannot be longer than __70 characters__, second line is always blank and other lines should be wrapped at __80 characters__! This makes the message easier to read on github as well as in various git tools.

### Subject Line

The subject line contains succinct description of the change.

#### Allowed <type>

 * feat (feature)
 * fix (bug fix)
 * docs (documentation)
 * style (formatting, missing semi colons, …)
 * refactor
 * test (when adding missing tests)
 * chore (maintain)
 * improve (improvement, e.g. enhanced feature)

#### Allowed <scope>

Scope could be anything specifying place of the commit change. For example in the [camunda-modeler project](https://github.com/camunda/camunda-modeler) this could be import, export, property panel, label, id, ...

#### <subject> text

 * use imperative, present tense: _change_ not _changed_ nor _changes_ or _changing_
 * do not capitalize first letter
 * do not append dot (.) at the end

### Message Body

 * just as in <subject> use imperative, present tense: _change_ not _changed_ nor _changes_ or _changing_
 * include motivation for the change and contrast it with previous behavior

### Message Footer

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

### FAQ for Geeks

##### Why to use imperative form in commit messages?
I.e. why to use _add test for #foo_ versus _added test for #foo_ or 
_adding test for foo_?

Makes commit logs way more readable. See the work you did during a 
commit as a work on an issue and the commit as solving that issue. 
Now write about for what issue the commit is a result rather than 
what you did or are currently doing.

__Example:__ You write a test for the function #foo. You commit the 
test. You use the commit message _add test for #foo_. Why? Because 
that is what the commit solves.

##### How to categorize commits which are direct follow ups to merges?
Use `chore(merge): <what>`.

##### I want to commit a micro change. What should I do?
Ask yourself, why it is only a micro change. Use feat = _docs_, _style_ 
or _chore_ depending on the change of your merge. Please see next question 
if you consider commiting work in progress.

##### I want to commit work in progress. What should I do?
Do not do it or do it (except for locally) or do it on a non public branch 
(ie. non master) if you need to share the stuff you do.

When you finished your work, [squash](http://gitready.com/advanced/2009/02/10/squashing-commits-with-rebase.html) the changes to commits with reasonable commit messages and push them on a public branch.

## Code Style Guidelines

### Braces

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

for readability there should also be a space after a loop, switch or if construct

Bad:
```php
if(done) {}
```

Good:
```php
if (done) {}
```

### Comments

Comments should only have a declarative function. Mostly used for PSR stuff.
Instead of comments you should try to write self-describing code.

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
