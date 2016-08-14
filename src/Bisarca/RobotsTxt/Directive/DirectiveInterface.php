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

use Bisarca\RobotsTxt\Exception\ExceptionInterface;

/**
 * Directives interface.
 */
interface DirectiveInterface
{
    /**
     * Constructor from raw row (e.g. "User-agent: *").
     *
     * @param string $raw Robots.txt record.
     *
     * @throws ExceptionInterface If there's a problem in directive creation.
     */
    public function __construct(string $raw);

    /**
     * Gets directive field (e.g. "user-agent").
     *
     * @return string
     */
    public static function getField(): string;

    /**
     * Gets directive value (e.g. "*").
     *
     * @return mixed
     */
    public function getValue();

    /**
     * Gets full directive (e.g. "User-agent: *").
     *
     * @return string
     */
    public function __toString(): string;
}
