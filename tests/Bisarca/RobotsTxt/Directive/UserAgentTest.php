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

use PHPUnit_Framework_TestCase;

/**
 * @covers Bisarca\RobotsTxt\Directive\UserAgent
 * @group unit
 */
class UserAgentTest extends PHPUnit_Framework_TestCase
{
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
}
