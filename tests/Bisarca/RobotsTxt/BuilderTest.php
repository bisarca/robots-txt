<?php

/*
 * This file is part of the bisarca/robots-txt package.
 *
 * (c) Emanuele Minotto <minottoemanuele@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bisarca\RobotsTxt;

use PHPUnit_Framework_TestCase;

/**
 * @covers Bisarca\RobotsTxt\Builder
 */
class BuilderTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Builder
     */
    protected $object;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->object = new Builder();
    }

    /**
     * @param string $fixture
     * @param string $expected
     *
     * @dataProvider buildDataProvider
     * @group functional
     */
    public function testBuild(string $fixture, string $expected)
    {
        $content = file_get_contents($fixture);

        $parser = new Parser();
        $rulesets = $parser->parse($content);

        $built = $this->object->build($rulesets);

        $this->assertStringEqualsFile($expected, $built);
    }

    /**
     * @return array
     */
    public function buildDataProvider(): array
    {
        return [
            [__DIR__.'/fixtures/1', __DIR__.'/fixtures/1_canonical'],
            [__DIR__.'/fixtures/2', __DIR__.'/fixtures/2_canonical'],
        ];
    }
}
