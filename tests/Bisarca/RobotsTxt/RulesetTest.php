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

    /**
     * @todo Implement testAdd().
     */
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
    public function testCount()
    {
        $this->assertCount(0, $this->object);

        $directive = $this->createMock(Directive\DirectiveInterface::class);

        $this->object->add($directive, $directive);

        $this->assertCount(2, $this->object);
    }

    /**
     * @param array  $rulesets
     * @param string $userAgent
     * @param string $path
     * @param bool   $expected
     *
     * @dataProvider isAllowedDataProvider
     */
    public function testIsAllowed(
        array $rulesets,
        string $userAgent,
        string $path,
        bool $expected
    ) {
        $this->object->add(...$rulesets);

        $this->assertSame(
            $expected,
            $this->object->isAllowed($userAgent, $path)
        );
    }

    /**
     * @return array
     */
    public function isAllowedDataProvider(): array
    {
        return [
            [
                [
                    new Directive\UserAgent('foo'),
                    new Directive\Disallow('/'),
                ],
                'foo',
                '/',
                false,
            ],
            [
                [
                    new Directive\UserAgent('foo'),
                    new Directive\Disallow('/'),
                ],
                'foo',
                '/t',
                false,
            ],
        ];
    }

    /**
     * @todo Implement testIsDisallowed().
     */
    public function testIsDisallowed()
    {
        $this->markTestIncomplete();
    }
}
