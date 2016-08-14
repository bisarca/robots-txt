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
 * @covers Bisarca\RobotsTxt\Directive\Sitemap
 * @group unit
 */
class SitemapTest extends TestCase
{
    /**
     * @dataProvider constructDataProvider
     */
    public function testConstruct(string $row, bool $valid, string $expected)
    {
        if (!$valid) {
            $this->setExpectedException(InvalidDirectiveException::class);
        }

        $directive = new Sitemap($row);

        $this->assertSame($expected, $directive->getValue());
    }

    /**
     * @return array
     */
    public function constructDataProvider(): array
    {
        $validUrl = 'http://example.com/sitemap.xml';
        $invalidUrls = [
            'http://example.com',
            'example.com/sitemap.xml',
            '/sitemap.xml',
            '/',
            ' ',
            '',
        ];

        $data = [
            ['Sitemap: '.$validUrl, true, $validUrl],
            ['Sitemap: '.$validUrl.' # comment', true, $validUrl],
            ['Sitemap: '.$validUrl.' #comment', true, $validUrl],
            ['Sitemap: '.$validUrl.'#comment', true, $validUrl],
        ];

        foreach ($invalidUrls as $invalidUrl) {
            $data[] = ['Sitemap: '.$invalidUrl, false, ''];
            $data[] = ['sitemap: '.$invalidUrl, false, ''];
            $data[] = [': '.$invalidUrl, false, ''];
            $data[] = [$invalidUrl, false, ''];
            $data[] = ['Sitemap: '.$invalidUrl.' # '.$validUrl, false, ''];
            $data[] = ['Sitemap: '.$invalidUrl.' #'.$validUrl, false, ''];
            $data[] = ['Sitemap: '.$invalidUrl.'# '.$validUrl, false, ''];
            $data[] = ['Sitemap: '.$invalidUrl.'#'.$validUrl, false, ''];
        }

        return $data;
    }

    public function testGetField()
    {
        $this->assertSame('sitemap', Sitemap::getField());
    }

    public function testGetValue()
    {
        $value = 'http://www.example.com/sitemap.xml';
        $directive = 'Sitemap: '.$value;

        $object = new Sitemap($directive);

        $this->assertSame($value, $object->getValue());
    }

    /**
     * @depends testGetValue
     */
    public function testToString()
    {
        $value = 'http://www.example.com/sitemap.xml';
        $directive = 'sitemap: '.$value;

        $object = new Sitemap($directive);

        $this->assertSame(ucfirst($directive), (string) $object);
    }
}
