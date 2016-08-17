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
use Bisarca\RobotsTxt\Directive\PathDirectiveInterface;
use Generator;

/**
 * Set of directives.
 */
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
        $path = trim($path) ?: PathDirectiveInterface::DEFAULT_PATH;

        foreach ($this->data as $directive) {
            if (
                $directive instanceof PathDirectiveInterface &&
                (
                    $directive->getValue() == $path ||
                    preg_match('#'.$directive->getRegex().'#', $path)
                )
            ) {
                return $directive instanceof Directive\Allow;
            }
        }

        return true;
    }

    /**
     * Gets ruleset comments for the developer.
     *
     * @return Generator
     */
    public function getComments(): Generator
    {
        yield from $this->getDirectives(Directive\Comment::class);
    }

    /**
     * Gets directives of a certain type.
     *
     * @param string|null $class
     *
     * @return DirectiveInterface[]
     */
    public function getDirectives(string $class = null): array
    {
        return array_filter($this->data, function ($element) use ($class) {
            return null === $class || $element instanceof $class;
        });
    }
}
