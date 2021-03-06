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

/**
 * Robots.txt file builder/serializer.
 */
class Builder
{
    /**
     * Builds the robots.txt file content.
     *
     * @param Rulesets $rulesets Required rulesets
     *
     * @return string
     */
    public function build(Rulesets $rulesets): string
    {
        $output = '';

        foreach ($rulesets as $ruleset) {
            $output .= $this->buildRuleset($ruleset).PHP_EOL;
        }

        return rtrim($output, PHP_EOL).PHP_EOL;
    }

    /**
     * Builds a single robots.txt' set of directives (ruleset).
     *
     * @param Ruleset $ruleset Required ruleset
     *
     * @return string
     */
    private function buildRuleset(Ruleset $ruleset): string
    {
        $output = '';

        foreach ($ruleset as $directive) {
            $output .= $directive.PHP_EOL;
        }

        return $output;
    }
}
