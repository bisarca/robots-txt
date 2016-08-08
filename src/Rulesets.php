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

use Countable;
use Iterator;

class Rulesets implements Iterator, Countable
{
    private $data = [];
    private $index = 0;

    public function current(): Ruleset
    {
        return $this->data[$this->index];
    }

    public function key()
    {
        return $this->index;
    }

    public function next()
    {
        ++$this->index;
    }

    public function rewind()
    {
        $this->index = 0;
    }

    public function valid(): bool
    {
        return $this->index >= 0 && $this->index < count($this->data);
    }

    public function count(): int
    {
        return count($this->data);
    }

    public function add(Ruleset ...$rulesets)
    {
        foreach ($rulesets as $ruleset) {
            $this->data[] = $ruleset;
        }
    }

    public function isAllowed(string $userAgent, string $path): bool
    {
        $allowed = false;

        foreach ($this as $ruleset) {
            $allowed = $allowed || $ruleset->isAllowed($userAgent, $path);
        }

        return $allowed;
    }

    public function isDisallowed(string $userAgent, string $path): bool
    {
        return !$this->isAllowed($userAgent, $path);
    }
}
