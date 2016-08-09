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

    /**
     * Constructor for required dependencies.
     *
     * @param DirectivesFactoryInterface|null $directivesFactory
     */
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

    /**
     * Parse robots.txt content.
     *
     * @param string $content
     *
     * @return Rulesets
     */
    public function parse(string $content): Rulesets
    {
        $rows = $this->extractRows($content);
        $groups = [];

        $counter = -1;
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

        foreach ($groups as $index => $group) {
            $groups[$index] = new Ruleset();
            $groups[$index]->add(...$group);
        }

        $rulesets = new Rulesets();
        $rulesets->add(...$groups);

        return $rulesets;
    }

    /**
     * Extract single rows from main content.
     *
     * @param string $content
     *
     * @return array
     */
    private function extractRows(string $content): array
    {
        // split by EOL
        $rows = explode(PHP_EOL, $content);

        // remove comments and wrapper spaces
        $rows = array_map(function ($row) {
            // see http://www.conman.org/people/spc/robots2.html
            return trim(preg_replace('/^(.*)#.*/', '$1', $row));
        }, $rows);

        // empty lines aren't useful
        return array_filter($rows);
    }
}
