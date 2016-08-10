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
 * @covers Bisarca\RobotsTxt\Rulesets
 * @group unit
 */
class RulesetsTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Rulesets
     */
    protected $object;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->object = new Rulesets();
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
        $ruleset = $this->createMock(Ruleset::class);

        $this->object->add($ruleset);

        $rulesets = iterator_to_array($this->object);

        $this->assertSame($rulesets[0], $ruleset);
    }

    /**
     * @depends testAdd
     */
    public function testCount()
    {
        $this->assertCount(0, $this->object);

        $ruleset = $this->createMock(Ruleset::class);

        $this->object->add($ruleset);
        $this->object->add($ruleset);

        $this->assertCount(2, $this->object);
    }

    /**
     * @depends testAdd
     */
    public function testClear()
    {
        $ruleset = $this->createMock(Ruleset::class);

        $this->object->add($ruleset);
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

        $ruleset = $this->createMock(Ruleset::class);

        $this->object->add($ruleset);
        $this->assertFalse($this->object->isEmpty());
    }
}
