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
use Countable;
use Iterator;

class Ruleset implements Iterator, Countable
{
    /**
     * Contained directives.
     *
     * @var array
     */
    private $data = [];

    /**
     * Internal index.
     *
     * @var int
     */
    private $index = 0;

    /**
     * {@inheritdoc}
     */
    public function current(): DirectiveInterface
    {
        return $this->data[$this->index];
    }

    /**
     * {@inheritdoc}
     */
    public function key()
    {
        return $this->index;
    }

    /**
     * {@inheritdoc}
     */
    public function next()
    {
        ++$this->index;
    }

    /**
     * {@inheritdoc}
     */
    public function rewind()
    {
        $this->index = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function valid(): bool
    {
        return $this->index >= 0 && $this->index < count($this->data);
    }

    /**
     * {@inheritdoc}
     */
    public function count(): int
    {
        return count($this->data);
    }

    /**
     * Adds directives.
     *
     * @param DirectiveInterface $directives
     */
    public function add(DirectiveInterface ...$directives)
    {
        foreach ($directives as $directive) {
            $this->data[] = $directive;
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
        $matchesAgent = false;

        foreach ($this->data as $directive) {
            if ($directive instanceof Directive\UserAgent) {
                if (in_array($directive->getValue(), ['*', $userAgent])) {
                    $matchesAgent = true;
                }
            }

            if ($directive instanceof Directive\Allow) {
                if (preg_match('#'.$directive->getRegex().'#', $path)) {
                    return true;
                }
            }

            if ($directive instanceof Directive\Disallow) {
                if (preg_match('#'.$directive->getRegex().'#', $path)) {
                    return false;
                }
            }
        }

        return true;
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
