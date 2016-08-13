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

use PHPUnit_Framework_TestCase;
use Traversable;

/**
 * @covers Bisarca\RobotsTxt\Ruleset
 * @group unit
 */
class RulesetTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Ruleset
     */
    protected $object;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->object = new Ruleset();
    }

    public function testGetIterator()
    {
        $this->assertInstanceOf(
            Traversable::class,
            $this->object->getIterator()
        );
    }

    public function testAdd()
    {
        $directive = $this->createMock(Directive\DirectiveInterface::class);

        $this->object->add($directive);

        $directives = iterator_to_array($this->object);

        $this->assertSame($directives[0], $directive);
    }

    /**
     * @depends testAdd
     */
    public function testRemove()
    {
        $directive = $this->createMock(Directive\DirectiveInterface::class);

        $this->object->add($directive);
        $this->assertCount(1, $this->object);

        $removed = $this->object->remove($directive);

        $this->assertCount(0, $this->object);
        $this->assertTrue($removed);

        $removed = $this->object->remove($directive);

        $this->assertCount(0, $this->object);
        $this->assertFalse($removed);

        $this->object->add(
            $this->createMock(Directive\DirectiveInterface::class)
        );

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
        $directive = $this->createMock(Directive\DirectiveInterface::class);

        $this->object->add($directive);
        $this->assertTrue($this->object->has($directive));

        $this->object->remove($directive);
        $this->assertFalse($this->object->has($directive));
    }

    /**
     * @depends testAdd
     */
    public function testCount()
    {
        $this->assertCount(0, $this->object);

        $directive = $this->createMock(Directive\DirectiveInterface::class);

        $this->object->add($directive);
        $this->object->add($directive);

        $this->assertSame(2, $this->object->count());
        $this->assertCount(2, $this->object);
    }

    /**
     * @depends testAdd
     */
    public function testClear()
    {
        $directive = $this->createMock(Directive\DirectiveInterface::class);

        $this->object->add($directive);
        $this->assertCount(1, $this->object);

        $this->object->clear();
        $this->assertCount(0, $this->object);
    }

    /**
     * @depends testAdd
     */
    public function testIsEmpty()
    {
        $this->assertTrue($this->object->isEmpty());

        $directive = $this->createMock(Directive\DirectiveInterface::class);

        $this->object->add($directive);
        $this->assertFalse($this->object->isEmpty());
    }
}
