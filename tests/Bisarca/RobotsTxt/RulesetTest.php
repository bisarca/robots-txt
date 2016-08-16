<?php

/*
 * This file is part of the bisarca/robots-txt package.
 *
 * (c) Emanuele Minotto <minottoemanuele@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bisarca\RobotsTxt;

use Bisarca\RobotsTxt\Directive\Allow;
use Bisarca\RobotsTxt\Directive\Comment;
use Bisarca\RobotsTxt\Directive\Disallow;
use Bisarca\RobotsTxt\Directive\UserAgent;

/**
 * @covers Bisarca\RobotsTxt\Ruleset
 * @group unit
 */
class RulesetTest extends AbstractSetTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->object = new Ruleset();
    }

    protected function getElement()
    {
        return $this->createMock(Directive\DirectiveInterface::class);
    }

    public function testAdd()
    {
        $directive = $this->getElement();

        $this->object->add($directive);

        $directives = iterator_to_array($this->object);

        $this->assertSame($directives[0], $directive);
    }

    /**
     * @depends testAdd
     */
    public function testRemove()
    {
        $directive = $this->getElement();

        $this->object->add($directive);
        $this->assertCount(1, $this->object);

        $removed = $this->object->remove($directive);

        $this->assertCount(0, $this->object);
        $this->assertTrue($removed);

        $removed = $this->object->remove($directive);

        $this->assertCount(0, $this->object);
        $this->assertFalse($removed);

        $this->object->add($this->getElement());

        $removed = $this->object->remove($directive);

        $this->assertCount(1, $this->object);
        $this->assertFalse($removed);
    }

    /**
     * @depends testAdd
     * @depends testRemove
     */
    public function testHas()
    {
        $directive = $this->getElement();

        $this->object->add($directive);
        $this->assertTrue($this->object->has($directive));

        $this->object->remove($directive);
        $this->assertFalse($this->object->has($directive));
    }

    public function testGetDirectives()
    {
        $directive = $this->getElement();

        $this->object->add($directive);

        $directives = $this->object->getDirectives(get_class($directive));

        $this->assertCount(1, $directives);
        $this->assertContainsOnly($directive, $directives);

        $directives = $this->object->getDirectives('stdClass');

        $this->assertCount(0, $directives);
    }

    /**
     * @depends testGetDirectives
     */
    public function testGetComments()
    {
        $directive1 = new Allow('Allow: /');
        $directive2 = new Comment('Comment: lorem ipsum');

        $this->object->add($directive1);
        $this->object->add($directive2);

        $comments = $this->object->getComments();
        $data = iterator_to_array($comments);

        $this->assertContainsOnlyInstancesOf(Comment::class, $data);
        $this->assertContainsOnly($directive2, $data);
        $this->assertCount(1, $data);
    }

    public function testIsUserAgentAllowedWithoutRules()
    {
        $this->object->add(new UserAgent('user-agent: *'));

        $this->assertTrue($this->object->isUserAgentAllowed('bot', '/'));
    }

    /**
     * @dataProvider isUserAgentDataProvider
     */
    public function testIsUserAgentAllowed(
        string $path,
        string $request,
        bool $matches
    ) {
        $this->object->add(new UserAgent('user-agent: *'));
        $this->object->add(new Allow('allow: '.$path));
        $this->object->add(new Disallow('disallow: /'));

        $this->assertSame(
            !$matches,
            $this->object->isUserAgentAllowed('bot', $request)
        );
    }

    /**
     * @dataProvider isUserAgentDataProvider
     * @depends testIsUserAgentAllowed
     */
    public function testIsUserAgentDisallowed(
        string $path,
        string $request,
        bool $matches
    ) {
        $this->object->add(new UserAgent('user-agent: *'));
        $this->object->add(new Disallow('disallow: '.$path));
        $this->object->add(new Allow('allow: /'));

        $this->assertSame(
            $matches,
            $this->object->isUserAgentAllowed('bot', $request)
        );
    }

    /**
     * @return array
     */
    public function isUserAgentDataProvider(): array
    {
        return [
            ['/', '', false],
            // Matches the root and any lower level URL
            ['/', '/', false],
            ['/', '/foo', false],
            ['/', '/foo/', false],
            ['/', '/foo/bar', false],
            // Equivalent to "/" -- the trailing wildcard is ignored.
            ['/*', '/', false],
            ['/*', '/foo', false],
            ['/*', '/foo/', false],
            ['/*', '/foo/bar', false],
            // Note the case-sensitive matching.
            ['/fish', '/fish', false],
            ['/fish', '/fish.html', false],
            ['/fish', '/fish/salmon.html', false],
            ['/fish', '/fishheads', false],
            ['/fish', '/fishheads/yummy.html', false],
            ['/fish', '/fish.php?id=anything', false],
            ['/fish', '/Fish.asp', true],
            ['/fish', '/catfish', true],
            ['/fish', '/?id=fish', true],
            // Equivalent to "/fish" -- the trailing wildcard is ignored.
            ['/fish*', '/fish', false],
            ['/fish*', '/fish.html', false],
            ['/fish*', '/fish/salmon.html', false],
            ['/fish*', '/fishheads', false],
            ['/fish*', '/fishheads/yummy.html', false],
            ['/fish*', '/fish.php?id=anything', false],
            ['/fish*', '/Fish.asp', true],
            ['/fish*', '/catfish', true],
            ['/fish*', '/?id=fish', true],
            // The trailing slash means this matches anything in this folder.
            ['/fish/', '/fish/', false],
            ['/fish/', '/fish/?id=anything', false],
            ['/fish/', '/fish/salmon.htm', false],
            ['/fish/', '/fish', true],
            ['/fish/', '/fish.html', true],
            ['/fish/', '/Fish/Salmon.asp', true],

            ['/*.php', '/filename.php', false],
            ['/*.php', '/folder/filename.php', false],
            ['/*.php', '/folder/filename.php?parameters', false],
            ['/*.php', '/folder/any.php.file.html', false],
            ['/*.php', '/filename.php/', false],
            ['/*.php', '/', true],
            ['/*.php', '/windows.PHP', true],

            ['/*.php$', '/filename.php', false],
            ['/*.php$', '/folder/filename.php', false],
            ['/*.php$', '/filename.php?parameters', true],
            ['/*.php$', '/filename.php/', true],
            ['/*.php$', '/filename.php5', true],
            ['/*.php$', '/windows.PHP', true],

            ['/fish*.php', '/fish.php', false],
            ['/fish*.php', '/fishheads/catfish.php?parameters', false],
            ['/fish*.php', '/Fish.PHP', true],
        ];
    }
}
