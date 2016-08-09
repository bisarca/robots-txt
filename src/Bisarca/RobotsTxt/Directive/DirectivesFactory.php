<?php

/*
 * This file is part of the bisarca/robots-txt package.
 *
 * (c) Emanuele Minotto <minottoemanuele@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bisarca\RobotsTxt\Directive;

use Exception;

class DirectivesFactory implements DirectivesFactoryInterface
{
    /**
     * Default directives.
     *
     * @var string
     */
    const DEFAULT_DIRECTIVES = [
        'allow' => Allow::class,
        'disallow' => Disallow::class,
        'user-agent' => UserAgent::class,
    ];

    /**
     * Registered directives.
     *
     * @var string[]
     */
    private $directives = self::DEFAULT_DIRECTIVES;

    /**
     * Gets the directives.
     *
     * @return string[]
     */
    public function getDirectives(): array
    {
        return $this->directives;
    }

    /**
     * Sets the Directives.
     *
     * @param string[] $directives
     */
    public function setDirectives(string ...$directives)
    {
        foreach ($directives as $directive) {
            $this->addDirective($directive);
        }
    }

    /**
     * Registers a Directive.
     *
     * @param string $class
     */
    public function addDirective(string $class)
    {
        if (!(
            class_exists($class) &&
            in_array(DirectiveInterface::class, class_implements($class))
        )) {
            throw new Exception();
        }

        $this->directives[$class::getField()] = $class;
    }

    /**
     * {@inheritdoc}
     */
    public function create(string $row): DirectiveInterface
    {
        $directives = array_filter(
            $this->directives,
            function ($field) use ($row) {
                return preg_match(sprintf('/^%s:\s+.+/i', $field), $row);
            },
            ARRAY_FILTER_USE_KEY
        );
        $directives = array_values($directives);

        // no directives found for this row
        // no action required
        if (empty($directives)) {
            throw new Exception();
        }

        // directives should be sorted by priority
        return new $directives[0]($row);
    }
}
