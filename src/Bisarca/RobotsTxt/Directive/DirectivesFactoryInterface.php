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

interface DirectivesFactoryInterface
{
    /**
     * Creates a directive from the raw line contained in the robots.txt file.
     *
     * @param string $raw Raw line.
     *
     * @return DirectiveInterface
     */
    public function create(string $row): DirectiveInterface;
}
