<?php

/*
 * This file is part of the bisarca/robots-txt package.
 *
 * (c) Emanuele Minotto <minottoemanuele@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Bisarca\RobotsTxt;

use Bisarca\RobotsTxt\Directive\DirectivesFactory;
use Bisarca\RobotsTxt\Directive\DirectivesFactoryInterface;

class Parser
{
    /**
     * Directives Factory.
     *
     * @var FactoryInterface
     */
    private $directivesFactory;

    public function __construct(DirectivesFactoryInterface $directivesFactory = null)
    {
        $this->setDirectivesFactory($directivesFactory ?: new DirectivesFactory());
    }

    /**
     * Gets the Directives Factory.
     *
     * @return DirectivesFactoryInterface
     */
    public function getDirectivesFactory(): DirectivesFactoryInterface
    {
        return $this->directivesFactory;
    }

    /**
     * Sets the Directives Factory.
     *
     * @param DirectivesFactoryInterface $directivesFactory
     */
    public function setDirectivesFactory(DirectivesFactoryInterface $directivesFactory)
    {
        $this->directivesFactory = $directivesFactory;
    }

    public function parse(string $content)
    {
        $rows = $this->extractRows($content);
        $groups = [];

        $counter = 0;
        $type = null;

        foreach ($rows as $row) {
            try {
                $directive = $this->directivesFactory->create($row);
            } catch (\Exception $exception) {
                continue;
            }

            $previous = $type;
            $type = $directive instanceof Directive\StartOfGroupInterface;

            if ($type && !($type && $previous)) {
                ++$counter;
            }

            if (!isset($groups[$counter])) {
                $groups[$counter] = [];
            }

            $groups[$counter][] = $directive;
        }

        return $groups;
    }

    private function extractRows(string $content): array
    {
        // split by EOL
        $rows = explode(PHP_EOL, $content);

        // remove comments and wrapper spaces
        $rows = array_map(function ($row) {
            return trim(preg_replace('/^(.*)#.*/', '$1', $row));
        }, $rows);

        // empty lines aren't useful
        return array_filter($rows);
    }
}
