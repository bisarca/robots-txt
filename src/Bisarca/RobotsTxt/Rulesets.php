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

use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

class Rulesets implements Countable, IteratorAggregate
{
    /**
     * Contained rulesets.
     *
     * @var array
     */
    private $data = [];

    /**
     * {@inheritdoc}
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * Adds rulesets.
     *
     * @param Ruleset $rulesets
     */
    public function add(Ruleset ...$rulesets)
    {
        foreach ($rulesets as $ruleset) {
            $this->data[] = $ruleset;
        }
    }

    /**
     * Checks if an user agent is allowed.
     *
     * @param string $userAgent
     * @param string $path
     *
     * @return bool
     */
    public function isAllowed(string $userAgent, string $path): bool
    {
        $allowed = false;

        foreach ($this as $ruleset) {
            $allowed = $allowed || $ruleset->isAllowed($userAgent, $path);
        }

        return $allowed;
    }

    /**
     * Checks if an user agent is not allowed.
     *
     * @param string $userAgent
     * @param string $path
     *
     * @return bool
     */
    public function isDisallowed(string $userAgent, string $path): bool
    {
        return !$this->isAllowed($userAgent, $path);
    }
}
