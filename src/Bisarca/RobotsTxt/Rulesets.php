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
     * Class constructor with optional initialization data.
     *
     * @param Ruleset[] $rulesets
     */
    public function __construct(Ruleset ...$rulesets)
    {
        $this->data = $rulesets;
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
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
     * Remove all contained elements.
     */
    public function clear()
    {
        $this->data = [];
    }

    /**
     * Checks if no elements are contained.
     *
     * @return bool
     */
    public function isEmpty(): bool
    {
        return empty($this->data);
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
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->data);
    }
}
