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
}
