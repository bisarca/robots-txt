Directives are simple instructions for bots trying to access to your domain,
reading these directives bots know when and how to visit the pages contained
in your website.

Each directive has the following format:

```
<directive> ':' [<whitespace>] <data> [<whitespace>] [<comment>] <end-of-line>
```

where `<data>` depend upon the directive and items in "[" and "]" are optional.
Unless otherwise noted, each directive can appear more than once in a given
rule set.


Interfaces
----------

Internally there are some interfaces to use:

**`Bisarca\RobotsTxt\Directive\DirectiveInterface`**

General directives interface.

This interface must be implemented to allow the directive to be considered by
the parser.

**`Bisarca\RobotsTxt\Directive\PathDirectiveInterface`**

Path based directives, currently only `Allow` and `Disallow`.

**`Bisarca\RobotsTxt\Directive\StartOfGroupInterface`**

Interface for directives starting a group.
Generally these directives are also group members.

Currently it's implemented only by the `UserAgent` directive.

**`Bisarca\RobotsTxt\Directive\GroupMemberInterface`**

Directives to be grouped.

**`Bisarca\RobotsTxt\Directive\NonGroupInterface`**

Directives indipendent from a group, like `Sitemap` or `Host`.
These are considered part of the robots.txt but not part of a single ruleset.


Supported Directives
--------------------

Currently supported directives are:

**User-Agent**

...


**Allow**

...


**Disallow**

...


**Sitemap**

...


**Host**

...


**Comment**

These are comments that the robot is encouraged to send back to the author/user
of the robot. All `Comment`'s in a rule set are to be sent back (at least,
that's the intention). This can be used to explain the robot policy of a site
(say, that one government site that hates robots).

To get the comments from a `Ruleset` there's the method `getComments` that
returns a [`Generator`](http://php.net/manual/en/language.generators.overview.php):

```php
foreach ($ruleset->getComments() as $comment) {
    echo $comment; // will write "Command: lorem ipsum"
}
```

To obtain all the comments contained in the robots.txt you just need to iterate
over all the `Ruleset`s:

```php
foreach ($rulesets as $ruleset) {
    foreach ($ruleset->getComments() as $comment) {
        echo $comment; // will write "Command: lorem ipsum"
    }
}
```
