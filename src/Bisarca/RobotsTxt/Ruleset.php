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
use Bisarca\RobotsTxt\Directive\UserAgent;
use DateTime;

class Ruleset extends AbstractSet
{
    /**
     * Class constructor with optional initialization data.
     *
     * @param DirectiveInterface[] $directives
     */
    public function __construct(DirectiveInterface ...$directives)
    {
        $this->data = $directives;
    }

    /**
     * Adds a directive.
     *
     * @param DirectiveInterface $directive
     */
    public function add(DirectiveInterface $directive)
    {
        $this->data[] = $directive;
    }

    /**
     * Checks if a directive is contained.
     *
     * @param DirectiveInterface $directive
     *
     * @return bool
     */
    public function has(DirectiveInterface $directive): bool
    {
        return false !== array_search($directive, $this->data, true);
    }

    /**
     * Remove an element.
     *
     * @param DirectiveInterface $directive
     *
     * @return bool
     */
    public function remove(DirectiveInterface $directive): bool
    {
        $key = array_search($directive, $this->data, true);

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
     * ...
     */
    public function getDelay()
    {
        // ...
    }

    /**
     * ...
     */
    public function getRequestRate()
    {
        // ...
    }

    /**
     * ...
     */
    public function hasVisitTime()
    {
        // ...
    }

    /**
     * ...
     */
    public function getVisitTime()
    {
        // ...
    }

    /**
     * ...
     */
    public function getComments()
    {
        // ...
    }
}
