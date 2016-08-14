<?php

/*
 * This file is part of the bisarca/robots-txt package.
 *
 * (c) Emanuele Minotto <minottoemanuele@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bisarca\RobotsTxt\Exception;

use PHPUnit\Framework\TestCase;

/**
 * @covers Bisarca\RobotsTxt\Exception\InvalidDirectiveException
 * @group unit
 */
class InvalidDirectiveExceptionTest extends TestCase
{
    public function testCreate()
    {
        $row = sha1(mt_rand());
        $exception = InvalidDirectiveException::create($row);

        $this->assertInstanceOf(ExceptionInterface::class, $exception);

        $this->expectException('Exception');
        $this->expectExceptionMessage(
            sprintf('The string "%s" isn\'t a valid directive', $row)
        );

        throw $exception;
    }
}
