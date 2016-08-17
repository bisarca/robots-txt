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

use Bisarca\RobotsTxt\Directive\DirectiveInterface;
use Bisarca\RobotsTxt\Directive\NonGroupInterface;
use Bisarca\RobotsTxt\Directive\StartOfGroupInterface;
use Bisarca\RobotsTxt\Exception\ExceptionInterface;
use Bisarca\RobotsTxt\Exception\MissingDirectiveException;

/**
 * Robots.txt file parser.
 */
class Parser
{
    /**
     * Registered directives.
     *
     * @var string[]
     */
    const DIRECTIVES = [
        'allow' => Directive\Allow::class,
        'comment' => Directive\Comment::class,
        'disallow' => Directive\Disallow::class,
        'host' => Directive\Host::class,
        'sitemap' => Directive\Sitemap::class,
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
                $directive = $this->createDirective($row);
            } catch (ExceptionInterface $exception) {
                continue;
            }

            $previous = $type;
            $type = $directive instanceof StartOfGroupInterface;

            if (
                $directive instanceof NonGroupInterface ||
                (
                    $type &&
                    !($type && $previous)
                )
            ) {
                ++$counter;
            }

            if (!isset($groups[$counter])) {
                $groups[$counter] = [];
            }

            $groups[$counter][] = $directive;
        }

        // any group-member records without a preceding
        // start-of-group record are ignored
        unset($groups[-1]);

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
            return trim(preg_replace('/^(.*)#.*/', '$1', $row));
        }, $rows);

        // empty lines aren't useful
        return array_filter($rows);
    }

    /**
     * Creates a directive from the raw line contained in the robots.txt file.
     *
     * @param string $raw Raw line
     *
     * @throws MissingDirectiveException If no directive is available
     *
     * @return DirectiveInterface
     */
    private function createDirective(string $row): DirectiveInterface
    {
        $directives = array_filter(
            self::DIRECTIVES,
            function ($field) use ($row) {
                return preg_match(sprintf('/^%s:\s+.+/i', $field), $row);
            },
            ARRAY_FILTER_USE_KEY
        );
        $directives = array_values($directives);

        // no directives found for this row
        // no action required
        if (empty($directives)) {
            throw MissingDirectiveException::create($row);
        }

        // directives should be sorted by priority
        return new $directives[0]($row);
    }
}
