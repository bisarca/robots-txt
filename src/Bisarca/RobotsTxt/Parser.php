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

use Exception;

class Parser
{
    /**
     * Registered directives.
     *
     * @var string[]
     */
    private $directives = [
        'allow' => Directive\Allow::class,
        'disallow' => Directive\Disallow::class,
        'user-agent' => Directive\UserAgent::class,
    ];

    /**
     * Parse robots.txt content.
     *
     * @param string $content
     *
     * @return Rulesets
     */
    public function parse(string $content): Rulesets
    {
        $rows = $this->extractRows($content);
        $groups = [];

        $counter = -1;
        $type = null;

        foreach ($rows as $row) {
            try {
                $directive = $this->getDirective($row);
            } catch (\Exception $exception) {
                continue;
            }

            $previous = $type;
            $type = $directive instanceof Directive\StartOfGroupInterface;

            if ($type && !($type && $previous)) {
                ++$counter;
            }

            if (!isset($groups[$counter])) {
                $groups[$counter] = [];
            }

            $groups[$counter][] = $directive;
        }

        foreach ($groups as $index => $group) {
            $groups[$index] = new Ruleset(...$group);
        }

        return new Rulesets(...$groups);
    }

    /**
     * Extract single rows from main content.
     *
     * @param string $content
     *
     * @return array
     */
    private function extractRows(string $content): array
    {
        // split by EOL
        $rows = explode(PHP_EOL, $content);

        // remove comments and wrapper spaces
        $rows = array_map(function ($row) {
            // see http://www.conman.org/people/spc/robots2.html
            return trim(preg_replace('/^(.*)#.*/', '$1', $row));
        }, $rows);

        // empty lines aren't useful
        return array_filter($rows);
    }

    /**
     * Creates a directive from the raw line contained in the robots.txt file.
     *
     * @param string $raw Raw line.
     *
     * @return DirectiveInterface
     */
    private function getDirective(string $row): Directive\DirectiveInterface
    {
        $directives = array_filter(
            $this->directives,
            function ($field) use ($row) {
                return preg_match(sprintf('/^%s:\s+.+/i', $field), $row);
            },
            ARRAY_FILTER_USE_KEY
        );
        $directives = array_values($directives);

        // no directives found for this row
        // no action required
        if (empty($directives)) {
            throw new Exception();
        }

        // directives should be sorted by priority
        return new $directives[0]($row);
    }
}
