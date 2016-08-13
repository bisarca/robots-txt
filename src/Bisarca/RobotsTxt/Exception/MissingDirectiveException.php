<?php

/*
 * This file is part of the bisarca/robots-txt package.
 *
 * (c) Emanuele Minotto <minottoemanuele@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bisarca\RobotsTxt\Exception;

use Exception;

/**
 * Exception for missing directives.
 */
class MissingDirectiveException extends Exception implements ExceptionInterface
{
    /**
     * Creates a new exception.
     *
     * @param string $row
     *
     * @return MissingDirectiveException
     */
    public static function create(string $row): MissingDirectiveException
    {
        return new self(sprintf('No directives found for "%s"', $row));
    }
}
