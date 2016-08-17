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

/**
 * Abstract set of utilities for internal sets.
 */
abstract class AbstractSet implements Countable, IteratorAggregate
{
    /**
     * Contained elements.
     *
     * @var array
     */
    protected $data = [];

    /**
     * {@inheritdoc}
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
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
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->data);
    }
}
