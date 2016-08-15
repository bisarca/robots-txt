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
 * @covers Bisarca\RobotsTxt\Directive\Host
 * @group unit
 */
class HostTest extends TestCase
{
    /**
     * @dataProvider constructDataProvider
     */
    public function testConstruct(string $row, string $expected)
    {
        if ('' === $expected) {
            $this->setExpectedException(InvalidDirectiveException::class);
        }

        $directive = new Host($row);

        $this->assertSame($expected, $directive->getValue());
    }

    /**
     * @return array
     */
    public function constructDataProvider(): array
    {
        return [
            ['Host: example.com', 'example.com'],
            ['Host:  example.com', 'example.com'],
            ['host: example.com', 'example.com'],
            ['Host:  ', ''],
            ['Host: ', ''],
            ['Host:', ''],
            ['host:  ', ''],
            ['host: ', ''],
            ['host:', ''],
            ['Host: example.com # comment', 'example.com'],
            ['Host:  example.com # comment', 'example.com'],
            ['host: example.com # comment', 'example.com'],
            ['Host:  # comment', ''],
            ['Host: # comment', ''],
            ['Host: # comment', ''],
            ['host:  # comment', ''],
            ['host: # comment', ''],
            ['host:# comment', ''],
            ['Host: example.com #comment', 'example.com'],
            ['Host:  example.com #comment', 'example.com'],
            ['host: example.com #comment', 'example.com'],
            ['Host:  #comment', ''],
            ['Host: #comment', ''],
            ['Host: #comment', ''],
            ['host:  #comment', ''],
            ['host: #comment', ''],
            ['host:#comment', ''],
        ];
    }

    /**
     * @requires function Pdp\PublicSuffixListManager::getList
     * @dataProvider constructWithValidationDataProvider
     */
    public function testConstructWithValidation(string $row)
    {
        $this->setExpectedException(InvalidDirectiveException::class);

        new Host($row);
    }

    /**
     * @return array
     */
    public function constructWithValidationDataProvider(): array
    {
        return [
            ['Host: example'],
            ['Host: example.faketld'],
        ];
    }

    public function testGetField()
    {
        $this->assertSame('host', Host::getField());
    }

    public function testGetValue()
    {
        $value = 'www.example.com';
        $directive = 'Host: '.$value;

        $object = new Host($directive);

        $this->assertSame($value, $object->getValue());
    }

    /**
     * @depends testGetValue
     */
    public function testToString()
    {
        $value = 'www.example.com';
        $directive = 'host: '.$value;

        $object = new Host($directive);

        $this->assertSame(ucfirst($directive), (string) $object);
    }
}
