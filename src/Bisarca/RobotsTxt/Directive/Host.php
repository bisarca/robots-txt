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
use Pdp\Parser;
use Pdp\PublicSuffixListManager;

/**
 * "Host" directive element.
 */
class Host implements NonGroupInterface
{
    /**
     * PHP Domain Parser (if available).
     *
     * @var Parser
     */
    private static $domainParser;

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
        if (!preg_match('/^host:\s+([^# ]+).*/i', $raw, $matches)) {
            throw InvalidDirectiveException::create($raw);
        }

        $host = trim($matches[1]);

        $this->validateHost($host, $raw);
        $this->value = $host;
    }

    /**
     * {@inheritdoc}
     */
    public static function getField(): string
    {
        return 'host';
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
        return sprintf('Host: %s', $this->value);
    }

    /**
     * Validates host (if PHP Domain Parser is available).
     *
     * @param string $host
     * @param string $raw
     */
    private function validateHost(string $host, string $raw)
    {
        if (
            class_exists(Parser::class) &&
            class_exists(PublicSuffixListManager::class) &&
            null === self::$domainParser
        ) {
            $pslManager = new PublicSuffixListManager();
            self::$domainParser = new Parser($pslManager->getList());
        }

        if (
            null !== self::$domainParser &&
            !self::$domainParser->isSuffixValid($host)
        ) {
            throw InvalidDirectiveException::create($raw);
        }
    }
}
