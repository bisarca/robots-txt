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
 * Exception for invalid directives.
 */
class InvalidDirectiveException extends Exception implements ExceptionInterface
{
    /**
     * Creates a new exception.
     *
     * @param string $row
     *
     * @return InvalidDirectiveException
     */
    public static function create(string $row): InvalidDirectiveException
    {
        return new self(
            sprintf('The string "%s" isn\'t a valid directive', $row)
        );
    }
}
