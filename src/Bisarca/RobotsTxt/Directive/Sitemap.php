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
        if (!preg_match('/^sitemap:\s+([^ #]+).*/i', $raw, $matches)) {
            throw InvalidDirectiveException::create($raw);
        }

        $url = trim($matches[1]);

        $options = FILTER_FLAG_SCHEME_REQUIRED | FILTER_FLAG_HOST_REQUIRED | FILTER_FLAG_PATH_REQUIRED;

        if (!filter_var($url, FILTER_VALIDATE_URL, $options)) {
            throw InvalidDirectiveException::create($raw);
        }

        $this->value = $url;
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
