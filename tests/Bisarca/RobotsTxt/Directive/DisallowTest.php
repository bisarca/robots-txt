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

use PHPUnit\Framework\TestCase;

/**
 * @covers Bisarca\RobotsTxt\Directive\Disallow
 * @group unit
 */
class DisallowTest extends TestCase
{
    public function testGetField()
    {
        $this->assertSame('disallow', Disallow::getField());
    }

    public function testGetValue()
    {
        $value = sha1(mt_rand());
        $directive = 'Disallow: '.$value;

        $object = new Disallow($directive);

        $this->assertSame($value, $object->getValue());
    }

    /**
     * @depends testGetValue
     */
    public function testToString()
    {
        $value = sha1(mt_rand());
        $directive = 'disallow: '.$value;

        $object = new Disallow($directive);

        $this->assertSame(ucfirst($directive), (string) $object);
    }

    /**
     * @param string $directive
     * @param string $expected
     *
     * @dataProvider getRegexDataProvider
     */
    public function testGetRegex(string $directive, string $expected)
    {
        $object = new Disallow($directive);

        $this->assertSame($expected, $object->getRegex());
    }

    public function getRegexDataProvider(): array
    {
        return [
            // "*" matches any sequence of characters (zero or more)
            ['Disallow: *foo', '.*foo'],
            ['Disallow: foo/*.bar', 'foo/.*.bar'],
            ['Disallow: foo/*', 'foo/.*'],
            // "?" matches any character
            ['Disallow: ?foo', '*foo'],
            ['Disallow: foo/?.bar', 'foo/*.bar'],
            ['Disallow: foo/?', 'foo/*'],
            // "\" supresses syntactic significance of a special character
            ['Disallow: *\*foo', '.*\*foo'],
            ['Disallow: \*foo/*\*.bar\*', '\*foo/.*\*.bar\*'],
            ['Disallow: foo/\**', 'foo/\*.*'],
            ['Disallow: \?foo', '\?foo'],
            ['Disallow: foo/\?*.bar', 'foo/\?.*.bar'],
            ['Disallow: foo/?\?', 'foo/*\?'],
        ];
    }
}
