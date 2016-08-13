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

use Bisarca\RobotsTxt\Directive\Sitemap;

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
        $directive1 = new Directive\Allow('Allow: /');
        $directive2 = new Sitemap('Sitemap: http://example.com/sitemap.xml');
        $ruleset = new Ruleset($directive1, $directive2);

        $this->object->add($ruleset);

        $sitemaps = $this->object->getSitemaps();
        $data = iterator_to_array($sitemaps);

        $this->assertContainsOnlyInstancesOf(Sitemap::class, $data);
        $this->assertContainsOnly($directive2, $data);
        $this->assertCount(1, $data);
    }
}
