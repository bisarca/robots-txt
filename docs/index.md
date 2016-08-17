The bisarca/robots-txt library is used to model and manage robots.txt files and
directives.

This library follows the common instructions (or _standard de facto_) about
how robots should interact with the domain pages, list a set of rules the robots
should follow, like what could be visited, which robots can visit a page,
when that page can be visited, etc...

It's based on some documents available online:

* [An Extended Standard for Robot Exclusion](http://www.conman.org/people/spc/robots2.html)
* [Google Robots.txt Specifications](https://developers.google.com/webmasters/control-crawl-index/docs/robots_txt)


Usage
=====

The goal of this library is to allow an easier interaction with robots.txt rules
while keeping everything.

It's based on a `Parser`, one or more set of directives (generally started by a
User-Agent directive), and the collection of these sets, called `Rulesets` that
represents the entire robots.txt.

```php
use Bisarca\RobotsTxt\Parser;

$parser = new Parser();

$content = file_get_contents('http://www.example.com/robots.txt');
$rulesets = $parser->parse($content); // instanceof \Bisarca\RobotsTxt\Rulesets

// is my-bot allowed to access to /path?
$allowed = $rulesets->isUserAgentAllowed('my-bot', '/path');

// is my-bot allowed to access (to /)?
$allowed = $rulesets->isUserAgentAllowed('my-bot');
```

This `Rulesets` contains one or more (could contain also zero) `Ruleset`, that
represents a set of [`Directive`](directives).

The `Ruleset` could be finally build into a clean version of the originally
parsed robots.txt, using

```php
use Bisarca\RobotsTxt\Builder;

// $rulesets instanceof \Bisarca\RobotsTxt\Rulesets

$builder = new Builder();
$content = $builder->build($rulesets);

file_put_contents('/path/to/your/public/robots.txt', $content);
```


Internal Flow
=============

![Library Flow](https://www.websequencediagrams.com/cgi-bin/cdraw?lz=dGl0bGUgRmxvdwoKUGFyc2VyLT5SdWxlc2V0czogUGFyc2luZyBmcm9tIGEgZGlydHkgcm9ib3RzLnR4dAoAIggAKAxJbnRlcm5hbC9Vc2VyIGVsYWJvcmF0aW9uACMLQnVpbGRlcjogAAQFaW5nIGEgY2xlYW4AUQw&s=napkin)
