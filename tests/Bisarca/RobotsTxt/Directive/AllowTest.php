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
 * @covers Bisarca\RobotsTxt\Directive\Allow
 * @group unit
 */
class AllowTest extends TestCase
{
    public function testGetField()
    {
        $this->assertSame('allow', Allow::getField());
    }

    public function testGetValue()
    {
        $value = sha1(mt_rand());
        $directive = 'Allow: '.$value;

        $object = new Allow($directive);

        $this->assertSame($value, $object->getValue());
    }

    /**
     * @depends testGetValue
     */
    public function testToString()
    {
        $value = sha1(mt_rand());
        $directive = 'allow: '.$value;

        $object = new Allow($directive);

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
        $object = new Allow($directive);

        $this->assertSame($expected, $object->getRegex());
    }

    /**
     * @return array
     */
    public function getRegexDataProvider(): array
    {
        return [
            // "*" matches any sequence of characters (zero or more)
            ['Allow: *foo', '.*foo'],
            ['Allow: foo/*.bar', 'foo/.*.bar'],
            ['Allow: foo/*', 'foo/.*'],
            // "?" matches any character
            ['Allow: ?foo', '*foo'],
            ['Allow: foo/?.bar', 'foo/*.bar'],
            ['Allow: foo/?', 'foo/*'],
            // "\" supresses syntactic significance of a special character
            ['Allow: *\*foo', '.*\*foo'],
            ['Allow: \*foo/*\*.bar\*', '\*foo/.*\*.bar\*'],
            ['Allow: foo/\**', 'foo/\*.*'],
            ['Allow: \?foo', '\?foo'],
            ['Allow: foo/\?*.bar', 'foo/\?.*.bar'],
            ['Allow: foo/?\?', 'foo/*\?'],
        ];
    }
}
