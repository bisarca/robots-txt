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
 * "Comment" directive element.
 */
class Comment implements StartOfGroupInterface
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
        if (!preg_match('/^comment:\s*([^ #]+).*/i', $raw, $matches)) {
            throw InvalidDirectiveException::create($raw);
        }

        $this->value = trim($matches[1]);
    }

    /**
     * {@inheritdoc}
     */
    public static function getField(): string
    {
        return 'comment';
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
        return sprintf('Comment: %s', $this->value);
    }
}
