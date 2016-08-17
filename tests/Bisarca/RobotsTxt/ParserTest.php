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

use PHPUnit\Framework\TestCase;

/**
 * @covers Bisarca\RobotsTxt\Parser
 * @group unit
 */
class ParserTest extends TestCase
{
    /**
     * @var Parser
     */
    protected $object;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->object = new Parser();
    }

    /**
     * @param string $content
     * @param array  $totals
     *
     * @dataProvider parseDataProvider
     * @runInSeparateProcess
     */
    public function testParse(string $content, array $totals)
    {
        $rulesets = $this->object->parse($content);

        $this->assertCount(count($totals), $rulesets);

        foreach ($rulesets as $index => $ruleset) {
            $this->assertCount($totals[$index], $ruleset);
        }
    }

    /**
     * @return array
     */
    public function parseDataProvider(): array
    {
        return [
            [file_get_contents(__DIR__.'/fixtures/1'), [15, 1, 2, 1, 1]],
            [file_get_contents(__DIR__.'/fixtures/2'), [2, 2]],
            [file_get_contents(__DIR__.'/fixtures/3'), [2]],
        ];
    }
}
