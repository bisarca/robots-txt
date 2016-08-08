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

class Builder
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

    public function build(Rulesets $rulesets): string
    {
        $output = '';

        foreach ($rulesets as $ruleset) {
            $output .= $this->buildRuleset($ruleset).PHP_EOL;
        }

        return $output;
    }

    private function buildRuleset(Ruleset $ruleset): string
    {
        $output = '';

        foreach ($ruleset as $directive) {
            $output .= $directive.PHP_EOL;
        }

        return $output;
    }
}
