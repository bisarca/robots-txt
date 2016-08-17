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

use Bisarca\RobotsTxt\Directive\Host;
use Bisarca\RobotsTxt\Directive\PathDirectiveInterface;
use Bisarca\RobotsTxt\Directive\Sitemap;
use Bisarca\RobotsTxt\Directive\UserAgent;
use Generator;

/**
 * Set of groups of directives.
 */
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
     * @param string $userAgent
     * @param string $path
     *
     * @return bool
     */
    public function isUserAgentAllowed(
        string $userAgent,
        string $path = PathDirectiveInterface::DEFAULT_PATH
    ): bool {
        // if the robots.txt is empty, than
        // the bot can always access
        if ($this->isEmpty()) {
            return true;
        }

        return $this
            ->getUserAgentRules($userAgent)
            ->isUserAgentAllowed($userAgent, $path);
    }

    /**
     * Gets roles for a specified user-agent.
     *
     * @param string $userAgent Default "*"
     *
     * @return Ruleset
     */
    public function getUserAgentRules(string $userAgent = UserAgent::ALL_AGENTS): Ruleset
    {
        // if the robots.txt is empty, than
        // no rules for that user-agent are defined
        if ($this->isEmpty()) {
            return new Ruleset();
        }

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
            yield from $ruleset->getDirectives(Sitemap::class);
        }
    }

    /**
     * Checks if the host directive is defined.
     *
     * @return bool
     */
    public function hasHost(): bool
    {
        foreach ($this->data as $ruleset) {
            if ($directives = $ruleset->getDirectives(Host::class)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Gets the host directive.
     *
     * @return Host
     */
    public function getHost(): Host
    {
        foreach ($this->data as $ruleset) {
            if ($directives = $ruleset->getDirectives(Host::class)) {
                return $directives[0];
            }
        }
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
                    $levenshtein = $lev;
                }
            }
        }

        return $top;
    }
}
