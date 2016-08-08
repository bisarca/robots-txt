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

class Disallow implements GroupMemberInterface
{
    private $value = '';

    public function __construct(string $raw)
    {
        $this->value = preg_replace('/^disallow:\s+(.+)/i', '$1', $raw);
    }

    public static function getField(): string
    {
        return 'disallow';
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function __toString(): string
    {
        return sprintf('Disallow: %s', $this->value);
    }

    public function getRegex(): string
    {
        // "*" matches any sequence of characters (zero or more)
        $value = str_replace('*', '.*', $this->value);

        // "?" matches any character
        $value = str_replace('?', '*', $value);

        // "\" supresses syntactic significance of a special character
        $value = str_replace('\*', '\?', $value);
        $value = str_replace('\.*', '\*', $value);

        return $value;
    }
}
