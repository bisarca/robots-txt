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
use Bisarca\RobotsTxt\Directive\Disallow;
use Bisarca\RobotsTxt\Directive\Host;
use Bisarca\RobotsTxt\Directive\Sitemap;
use Bisarca\RobotsTxt\Directive\UserAgent;
use ReflectionClass;

/**
 * @covers Bisarca\RobotsTxt\Rulesets
 * @group unit
 */
class RulesetsTest extends AbstractSetTest
{
    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->object = new Rulesets();
    }

    protected function getElement()
    {
        return $this->createMock(Ruleset::class);
    }

    public function testAdd()
    {
        $ruleset = $this->getElement();

        $this->object->add($ruleset);

        $rulesets = iterator_to_array($this->object);

        $this->assertSame($rulesets[0], $ruleset);
    }

    /**
     * @depends testAdd
     */
    public function testRemove()
    {
        $ruleset = $this->getElement();

        $this->object->add($ruleset);
        $this->assertCount(1, $this->object);

        $removed = $this->object->remove($ruleset);

        $this->assertCount(0, $this->object);
        $this->assertTrue($removed);

        $removed = $this->object->remove($ruleset);

        $this->assertCount(0, $this->object);
        $this->assertFalse($removed);

        $this->object->add($this->getElement());

        $removed = $this->object->remove($ruleset);

        $this->assertCount(1, $this->object);
        $this->assertFalse($removed);
    }

    /**
     * @depends testAdd
     * @depends testRemove
     */
    public function testHas()
    {
        $ruleset = $this->getElement();

        $this->object->add($ruleset);
        $this->assertTrue($this->object->has($ruleset));

        $this->object->remove($ruleset);
        $this->assertFalse($this->object->has($ruleset));
    }

    public function testGetSitemaps()
    {
        $directive1 = new Allow('Allow: /');
        $directive2 = new Sitemap('Sitemap: http://example.com/sitemap.xml');
        $ruleset = new Ruleset($directive1, $directive2);

        $this->object->add($ruleset);

        $sitemaps = $this->object->getSitemaps();
        $data = iterator_to_array($sitemaps);

        $this->assertContainsOnlyInstancesOf(Sitemap::class, $data);
        $this->assertContainsOnly($directive2, $data);
        $this->assertCount(1, $data);
    }

    /**
     * @link https://developers.google.com/webmasters/control-crawl-index/docs/robots_txt#order-of-precedence-for-user-agents
     *
     * @dataProvider getTopUserAgentDataProvider
     */
    public function testGetTopUserAgent(string $userAgent, int $expected)
    {
        $reflectionClass = new ReflectionClass($this->object);
        $reflectionMethod = $reflectionClass->getMethod('getTopUserAgent');
        $reflectionMethod->setAccessible(true);

        $groups = [
            new UserAgent('user-agent: googlebot-news'),
            new UserAgent('user-agent: *'),
            new UserAgent('user-agent: googlebot'),
        ];

        foreach ($groups as $group) {
            $this->object->add(new Ruleset($group));
        }
        $this->assertCount(3, $this->object);

        $this->assertEquals(
            $groups[$expected - 1],
            $reflectionMethod->invokeArgs($this->object, [$userAgent])
        );
    }

    /**
     * @dataProvider getTopUserAgentDataProvider
     */
    public function testGetUserAgentRules(string $userAgent, int $expected)
    {
        // if the robots.txt is empty, than the bot can always access
        $this->assertCount(0, $this->object->getUserAgentRules($userAgent));

        $groups = [
            [
                new UserAgent('user-agent: googlebot-news'),
                new Allow('allow: /'),
            ],
            [
                new UserAgent('user-agent: *'),
                new Disallow('disallow: /search'),
            ],
            [
                new UserAgent('user-agent: googlebot'),
                new Allow('allow: /index.html'),
            ],
        ];

        foreach ($groups as $group) {
            $this->object->add(new Ruleset(...$group));
        }
        $this->assertCount(3, $this->object);

        $rules = $this->object->getUserAgentRules($userAgent);

        $this->assertInstanceOf(Ruleset::class, $rules);
        $this->assertEquals($groups[$expected - 1], iterator_to_array($rules));
    }

    /**
     * @return array
     */
    public function getTopUserAgentDataProvider(): array
    {
        return [
            // Only the most specific group is followed, all others are ignored.
            ['Googlebot-News', 1],
            ['Googlebot', 3],
            // There is no specific googlebot-images group,
            // so the more generic group is followed.
            ['Googlebot-Image', 3],
            // These images are crawled for and by Googlebot News,
            // therefore only the Googlebot News group is followed.
            ['Googlebot-News', 1],
            ['Otherbot', 2],
            // Even if there is an entry for a related crawler,
            // it is only valid if it is specifically matching.
            ['Otherbot-News', 2],
        ];
    }

    /**
     * @runInSeparateProcess
     */
    public function testGetHost()
    {
        $host = new Host('host: www.example.com');
        $ruleset = new Ruleset($host);

        $this->object->add($ruleset);
        $this->assertSame($host, $this->object->getHost());

        $ruleset2 = new Ruleset(new Host('host: example.com'));
        $this->object->add($ruleset2);
        $this->assertSame($host, $this->object->getHost());

        $this->object->remove($ruleset);
        $this->object->remove($ruleset2);

        $this->setExpectedException('TypeError');
        $this->object->getHost();
    }

    /**
     * @runInSeparateProcess
     */
    public function testHasHost()
    {
        $host = new Host('host: www.example.com');
        $ruleset = new Ruleset($host);

        $this->object->add($ruleset);
        $this->assertTrue($this->object->hasHost());

        $ruleset2 = new Ruleset(new Host('host: example.com'));
        $this->object->add($ruleset2);
        $this->assertTrue($this->object->hasHost());

        $this->object->remove($ruleset);
        $this->object->remove($ruleset2);

        $this->assertFalse($this->object->hasHost());
    }

    /**
     * @dataProvider isUserAgentDataProvider
     */
    public function testIsUserAgentAllowed(
        string $path,
        string $request,
        bool $matches
    ) {
        // if the robots.txt is empty, than the bot can always access
        $this->assertTrue($this->object->isUserAgentAllowed('bot', '/path'));
        $this->assertTrue($this->object->isUserAgentAllowed('bot'));

        $this->object->add(new Ruleset(
            new UserAgent('user-agent: *'),
            new Allow('allow: '.$path),
            new Disallow('disallow: /')
        ));

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
        $this->object->add(new Ruleset(
            new UserAgent('user-agent: *'),
            new Disallow('disallow: '.$path),
            new Allow('allow: /')
        ));

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
        return (new RulesetTest())
            ->isUserAgentDataProvider();
    }
}
