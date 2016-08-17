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
==========

Internally there are some interfaces to use:

DirectiveInterface
------------------

General directives interface.

This interface must be implemented to allow the directive to be considered by
the parser.

PathDirectiveInterface
----------------------

Path based directives, currently only `Allow` and `Disallow`.

StartOfGroupInterface
---------------------

Interface for directives starting a group.
Generally these directives are also group members.

Currently it's implemented only by the `UserAgent` directive.

GroupMemberInterface
--------------------

Directives to be grouped.

NonGroupInterface
-----------------

Directives indipendent from a group, like `Sitemap` or `Host`.
These are considered part of the robots.txt but not part of a single ruleset.


Supported Directives
====================

Currently supported directives are:

User-Agent
----------

This directive defines the robot the following rules applies to (e.g. Googlebot).


Allow
-----

This directive (if included) and the `Disallow` directive are to be processed in
the order they appear in the rule set.
This is to simplify the processing, avoid ambiguity and allow more control over
what is and isn't allowed.

If a URL is not covered by any allow or disallow rules,
then the URL is to be allowed.


Disallow
--------

This directive and the Allow: directive (if included) are to be processed in the
order they appear in the rule set.
This is to simplify the processing, avoid ambiguity and allow more control over
what is and isn't allowed.

If a URL is not covered by any allow or disallow rules,
then the URL is to be allowed.


The directives above shouldn't be used directly, use them from the `Rulesets`:

```php

if ($rulesets->isUserAgentAllowed('my-bot', '/path')) {
    // access allowed
} else {
    // access denied
}

```


Sitemap
-------

This directive is independent from the User-Agent, so it doesn't matter
where it is placed in the file.

A Sitemap index file can be included the location of just that file.
More than one `Sitemap` could be specified in the robots.txt file.

To obtain all the sitemaps, just call:

```php

foreach ($rulesets->getSitemaps() as $sitemap) {
    // $sitemap instanceof \Bisarca\RobotsTxt\Directive\Sitemap

    echo $sitemap->getValue(); // http://www.example.com/sitemap.xml
}

```


Host
----

The `Host` directive is used to define the main domain the bot should follow.
This directive does not guarantee that the specified main domain will be selected.

For every robots.txt file, only one Host directive is processed.
If several directives are indicated in the file, the robot will use the first one.

```php

if ($rulesets->hasHost()) {
    $host = $rulesets->getHost(); // instanceof \Bisarca\RobotsTxt\Directive\Host
}

```


Comment
-------

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
