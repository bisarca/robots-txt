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

use Bisarca\RobotsTxt\Directive\UserAgent;
use DateTime;
use Generator;

class Rulesets extends AbstractSet
{
    /**
     * Class constructor with optional initialization data.
     *
     * @param Ruleset[] $rulesets
     */
    public function __construct(Ruleset ...$rulesets)
    {
        $this->data = $rulesets;
    }

    /**
     * Adds a ruleset.
     *
     * @param Ruleset $ruleset
     */
    public function add(Ruleset $ruleset)
    {
        $this->data[] = $ruleset;
    }

    /**
     * Checks if a ruleset is contained.
     *
     * @param Ruleset $ruleset
     *
     * @return bool
     */
    public function has(Ruleset $ruleset): bool
    {
        return false !== array_search($ruleset, $this->data, true);
    }

    /**
     * Remove an element.
     *
     * @param Ruleset $ruleset
     *
     * @return bool
     */
    public function remove(Ruleset $ruleset): bool
    {
        $key = array_search($ruleset, $this->data, true);

        if (false !== $key) {
            unset($this->data[$key]);
            $this->data = array_values($this->data);

            return true;
        }

        return false;
    }

    /**
     * ...
     */
    public function isUserAgentAllowed(
        string $userAgent,
        string $path = null,
        DateTime $lastVisit = null
    ) {
        // ...
    }

    /**
     * ...
     */
    public function getUserAgentRules(string $userAgent = UserAgent::ALL_AGENTS)
    {
        // ...
    }

    /**
     * Extract sitemap directives.
     *
     * @return Generator
     */
    public function getSitemaps(): Generator
    {
        foreach ($this->data as $ruleset) {
            foreach ($ruleset as $directive) {
                if ($directive instanceof Directive\Sitemap) {
                    yield $directive;
                }
            }
        }
    }

    /**
     * ...
     */
    public function hasHost()
    {
        // ...
    }

    /**
     * ...
     */
    public function getHost()
    {
        // ...
    }

    /**
     * ...
     */
    public function getCleanParams(string $path = null)
    {
        // ...
    }
}
