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
 * @covers Bisarca\RobotsTxt\Directive\Sitemap
 * @group unit
 */
class SitemapTest extends TestCase
{
    public function testGetField()
    {
        $this->assertSame('sitemap', Sitemap::getField());
    }

    public function testGetValue()
    {
        $value = sha1(mt_rand());
        $directive = 'Sitemap: '.$value;

        $object = new Sitemap($directive);

        $this->assertSame($value, $object->getValue());
    }

    /**
     * @depends testGetValue
     */
    public function testToString()
    {
        $value = sha1(mt_rand());
        $directive = 'sitemap: '.$value;

        $object = new Sitemap($directive);

        $this->assertSame(ucfirst($directive), (string) $object);
    }
}
