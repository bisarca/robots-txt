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

    /**
     * @todo   Implement testCurrent().
     */
    public function testCurrent()
    {
        $this->markTestIncomplete();
    }

    /**
     * @todo   Implement testKey().
     */
    public function testKey()
    {
        $this->markTestIncomplete();
    }

    /**
     * @todo   Implement testNext().
     */
    public function testNext()
    {
        $this->markTestIncomplete();
    }

    /**
     * @todo   Implement testRewind().
     */
    public function testRewind()
    {
        $this->markTestIncomplete();
    }

    /**
     * @todo   Implement testValid().
     */
    public function testValid()
    {
        $this->markTestIncomplete();
    }

    /**
     * @todo Implement testCount().
     */
    public function testCount()
    {
        $this->markTestIncomplete();
    }

    /**
     * @todo Implement testAdd().
     */
    public function testAdd()
    {
        $this->markTestIncomplete();
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
     * @todo   Implement testIsDisallowed().
     */
    public function testIsDisallowed()
    {
        $this->markTestIncomplete();
    }
}
