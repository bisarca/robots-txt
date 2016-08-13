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

use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Traversable;

/**
 * @covers Bisarca\RobotsTxt\AbstractSet
 * @group unit
 */
class AbstractSetTest extends TestCase
{
    /**
     * @var AbstractSet
     */
    protected $object;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->object = $this->getMockForAbstractClass(AbstractSet::class);
    }

    protected function getElement()
    {
        return new \stdClass();
    }

    public function testGetIterator()
    {
        $this->assertInstanceOf(
            Traversable::class,
            $this->object->getIterator()
        );
    }

    public function testClear()
    {
        $element = $this->getElement();

        $this->add($element);
        $this->assertCount(1, $this->object);

        $this->object->clear();
        $this->assertCount(0, $this->object);
    }

    public function testIsEmpty()
    {
        $this->assertTrue($this->object->isEmpty());

        $element = $this->getElement();

        $this->add($element);
        $this->assertFalse($this->object->isEmpty());
    }

    public function testCount()
    {
        $this->assertSame(0, $this->object->count());
        $this->assertCount(0, $this->object);

        $element = $this->getElement();

        $this->add($element);
        $this->add($element);

        $this->assertSame(2, $this->object->count());
        $this->assertCount(2, $this->object);
    }

    private function add($element)
    {
        $reflection = new ReflectionClass($this->object);
        $property = $reflection->getProperty('data');
        $property->setAccessible(true);

        $data = $property->getValue($this->object);
        $data[] = $element;

        $property->setValue($this->object, $data);
    }
}
