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

/**
 * Path based directives interface.
 */
interface PathDirectiveInterface extends DirectiveInterface
{
    /**
     * Default directive path.
     *
     * @var string
     */
    const DEFAULT_PATH = '/';

    /**
     * Gets the regular expression associated.
     *
     * @return string
     */
    public function getRegex(): string;
}
