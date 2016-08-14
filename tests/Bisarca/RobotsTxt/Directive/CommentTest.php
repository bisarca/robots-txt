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
 * @covers Bisarca\RobotsTxt\Directive\Comment
 * @group unit
 */
class CommentTest extends TestCase
{
    /**
     * @dataProvider constructDataProvider
     */
    public function testConstruct(string $row, bool $valid, string $expected)
    {
        if (!$valid) {
            $this->setExpectedException(InvalidDirectiveException::class);
        }

        $directive = new Comment($row);

        $this->assertSame($expected, $directive->getValue());
    }

    /**
     * @return array
     */
    public function constructDataProvider(): array
    {
        return [
            ['Comment: A', true, 'A'],
            ['Comment:  A', true, 'A'],
            ['comment: A', true, 'A'],
            ['Comment:  ', false, ''],
            ['Comment: ', false, ''],
            ['Comment:', false, ''],
            ['comment:  ', false, ''],
            ['comment: ', false, ''],
            ['comment:', false, ''],
        ];
    }

    public function testGetField()
    {
        $this->assertSame('comment', Comment::getField());
    }

    public function testGetValue()
    {
        $value = sha1(mt_rand());
        $directive = 'Comment: '.$value;

        $object = new Comment($directive);

        $this->assertSame($value, $object->getValue());
    }

    /**
     * @depends testGetValue
     */
    public function testToString()
    {
        $value = sha1(mt_rand());
        $directive = 'comment: '.$value;

        $object = new Comment($directive);

        $this->assertSame(ucfirst($directive), (string) $object);
    }
}
