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
 * "User-agent" directive element.
 */
class UserAgent implements StartOfGroupInterface
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
        $this->value = preg_replace('/^user-agent:\s+(.+)/i', '$1', $raw);
    }

    /**
     * {@inheritdoc}
     */
    public static function getField(): string
    {
        return 'user-agent';
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
        return sprintf('User-agent: %s', $this->value);
    }
}
