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

    /**
     * @todo Implement testAdd().
     */
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

        $this->object->add($ruleset, $ruleset);

        $this->assertCount(2, $this->object);
    }

    /**
     * @todo Implement testIsAllowed().
     */
    public function testIsAllowed()
    {
        $this->markTestIncomplete();
    }

    /**
     * @todo Implement testIsDisallowed().
     */
    public function testIsDisallowed()
    {
        $this->markTestIncomplete();
    }
}
