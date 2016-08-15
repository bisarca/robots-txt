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
     * Checks if a user agent is allowed.
     *
     * @param string        $userAgent
     * @param string|null   $path
     * @param DateTime|null $lastVisit
     *
     * @return bool
     */
    public function isUserAgentAllowed(
        string $userAgent,
        string $path = '',
        DateTime $lastVisit = null
    ): bool {
        return $this
            ->getUserAgentRules($userAgent)
            ->isUserAgentAllowed($userAgent, $path, $lastVisit);
    }

    /**
     * Gets roles for a specified user-agent.
     *
     * @param string $userAgent Default "*".
     *
     * @return Ruleset
     */
    public function getUserAgentRules(string $userAgent = UserAgent::ALL_AGENTS): Ruleset
    {
        $topUserAgent = $this->getTopUserAgent($userAgent);

        return array_values(array_filter(
            $this->data,
            function (Ruleset $ruleset) use ($topUserAgent) {
                return $ruleset->has($topUserAgent);
            }
        ))[0];
    }

    /**
     * Extract sitemap directives.
     *
     * @return Generator
     */
    public function getSitemaps(): Generator
    {
        foreach ($this->data as $ruleset) {
            yield from $ruleset->getDirectives(Directive\Sitemap::class);
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

    /**
     * Gets top User-Agent directive.
     *
     * @param string $userAgent
     *
     * @return UserAgent
     */
    private function getTopUserAgent(string $userAgent): UserAgent
    {
        $userAgent = mb_strtolower($userAgent);
        $top = null;
        $levenshtein = PHP_INT_MAX;
        $directives = [];

        foreach ($this->data as $ruleset) {
            $directives = array_filter(
                $ruleset->getDirectives(UserAgent::class),
                function ($directive) use ($userAgent) {
                    return $directive->isMatching($userAgent);
                }
            );

            foreach ($directives as $index => $directive) {
                $localUa = mb_strtolower($directive->getValue());
                $lev = levenshtein($userAgent, $localUa);

                if (0 === $lev) {
                    return $directive;
                }

                if ($lev < $levenshtein) {
                    $top = $directive;
                }
            }
        }

        return $top;
    }
}
