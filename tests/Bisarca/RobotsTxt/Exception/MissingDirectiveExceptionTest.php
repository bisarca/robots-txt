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

use PHPUnit_Framework_TestCase;

/**
 * @covers Bisarca\RobotsTxt\Exception\MissingDirectiveException
 * @group unit
 */
class MissingDirectiveExceptionTest extends PHPUnit_Framework_TestCase
{
    public function testCreate()
    {
        $row = sha1(mt_rand());
        $exception = MissingDirectiveException::create($row);

        $this->assertInstanceOf(ExceptionInterface::class, $exception);

        $this->expectException('Exception');
        $this->expectExceptionMessage(
            sprintf('No directives found for "%s"', $row)
        );

        throw $exception;
    }
}
