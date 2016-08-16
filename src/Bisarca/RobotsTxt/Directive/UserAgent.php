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
     * When the asterisk is used, than all the user-agents are included.
     *
     * @var string
     */
    const ALL_AGENTS = '*';

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
        if (!preg_match('/^user-agent:\s*([^# ]+).*/i', $raw, $matches)) {
            throw InvalidDirectiveException::create($raw);
        }

        $this->value = trim($matches[1]);
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

    /**
     * Checks if a user-agent is matching current user agent.
     *
     * @param string $userAgent
     *
     * @return bool
     */
    public function isMatching(string $userAgent): bool
    {
        // "*" matches any sequence of characters (zero or more)
        $value = str_replace('*', '.*', $this->value);

        return 1 === preg_match('#^'.$value.'#', trim($userAgent));
    }
}
