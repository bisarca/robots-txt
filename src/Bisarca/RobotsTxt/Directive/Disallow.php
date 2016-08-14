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

use Bisarca\RobotsTxt\Exception\InvalidDirectiveException;

/**
 * "Disallow" directive element.
 */
class Disallow implements GroupMemberInterface
{
    /**
     * Directive value.
     *
     * @var string
     */
    private $value = '';

    /**
     * {@inheritdoc}
     */
    public function __construct(string $raw)
    {
        if (!preg_match('/^disallow:([^#]*).*/i', $raw, $matches)) {
            throw InvalidDirectiveException::create($raw);
        }

        $this->value = trim($matches[1]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getField(): string
    {
        return 'disallow';
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return sprintf('Disallow: %s', $this->value);
    }

    /**
     * Gets regular expression associated.
     *
     * @return string
     */
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
