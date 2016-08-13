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
 * "Sitemap" directive element.
 */
class Sitemap implements NonGroupInterface
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
        $this->value = preg_replace('/^sitemap:\s+(.+)/i', '$1', $raw);
    }

    /**
     * {@inheritdoc}
     */
    public static function getField(): string
    {
        return 'sitemap';
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
        return sprintf('Sitemap: %s', $this->value);
    }
}
