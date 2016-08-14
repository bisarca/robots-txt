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
        if (!preg_match('/^user-agent:\s+([^ #]+).*/i', $raw, $matches)) {
            throw InvalidDirectiveException::create($raw);
        }

        $this->value = $matches[1];
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
