<?php

/*
 * This file is part of the bisarca/robots-txt package.
 *
 * (c) Emanuele Minotto <minottoemanuele@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bisarca\RobotsTxt\Directive;

use Bisarca\RobotsTxt\Exception\InvalidDirectiveException;
use PHPUnit\Framework\TestCase;

/**
 * @covers Bisarca\RobotsTxt\Directive\UserAgent
 * @group unit
 */
class UserAgentTest extends TestCase
{
    /**
     * @dataProvider constructDataProvider
     */
    public function testConstruct(string $row, bool $valid, string $expected)
    {
        if (!$valid) {
            $this->setExpectedException(InvalidDirectiveException::class);
        }

        $directive = new UserAgent($row);

        $this->assertSame($expected, $directive->getValue());
    }

    /**
     * @return array
     */
    public function constructDataProvider(): array
    {
        return [
            ['User-Agent: A', true, 'A'],
            ['User-Agent:  A', true, 'A'],
            ['user-agent: A', true, 'A'],
            ['User-Agent:  ', false, ''],
            ['User-Agent: ', false, ''],
            ['User-Agent:', false, ''],
            ['user-agent:  ', false, ''],
            ['user-agent: ', false, ''],
            ['user-agent:', false, ''],
        ];
    }

    public function testGetField()
    {
        $this->assertSame('user-agent', UserAgent::getField());
    }

    public function testGetValue()
    {
        $value = sha1(mt_rand());
        $directive = 'User-agent: '.$value;

        $object = new UserAgent($directive);

        $this->assertSame($value, $object->getValue());
    }

    /**
     * @depends testGetValue
     */
    public function testToString()
    {
        $value = sha1(mt_rand());
        $directive = 'user-agent: '.$value;

        $object = new UserAgent($directive);

        $this->assertSame(ucfirst($directive), (string) $object);
    }

    /**
     * @dataProvider isMatchingDataProvider
     */
    public function testIsMatching(
        string $directive,
        string $userAgent,
        bool $matching
    ) {
        $object = new UserAgent('user-agent: '.$directive);

        $this->assertSame(
            $matching,
            $object->isMatching($userAgent)
        );
    }

    /**
     * @return array
     */
    public function isMatchingDataProvider(): array
    {
        return [
            ['*', 'bot', true],
            ['bot', 'bot', true],
            ['bot*', 'bot', true],
            ['bot*', 'robot', false],
            ['googlebot', 'googlebot/1.2', true],
            ['not', 'bot', false],
            ['notbot', 'bot', false],
        ];
    }
}
