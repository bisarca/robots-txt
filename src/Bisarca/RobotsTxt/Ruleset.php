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
use Bisarca\RobotsTxt\Directive\DirectiveInterface;
use IteratorAggregate;
use Traversable;

class Ruleset implements IteratorAggregate
{
    /**
     * Contained directives.
     *
     * @var DirectiveInterface[]
     */
    private $data = [];

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
     * {@inheritdoc}
     */
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->data);
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
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->data);
    }
}
