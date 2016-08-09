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

use PHPUnit_Framework_TestCase;

/**
 * @covers Bisarca\RobotsTxt\Parser
 * @group unit
 */
class ParserTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var Parser
     */
    protected $object;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->object = new Parser();
    }

    /**
     * @param string $content
     *
     * @dataProvider parseDataProvider
     */
    public function testParse(string $content)
    {
        $parsed = $this->object->parse($content);

        $this->assertTrue(true);
    }

    /**
     * @return array
     */
    public function parseDataProvider()
    {
        return [
            ["#-------------------------------------------------------------
# The following robots understand the 2.0 spec, so we
# can allow them a bit more freedom about what to do
#--------------------------------------------------------------

User-agent: alfred
User-agent: newchives
User-agent: oscarbot
Robot-version: 2.0    # uses 2.0 spec
Allow: *index.html      # allow any index pages
Allow: /images/index.html   # make sure we index this
Allow: /blackhole/index.html    # and we allow this page to be indexed
Allow: /blackhole/info*     # as well as these
Disallow: *         # nothing else will be allowed
Disallow: *.shtml       # don't index server include files
Disallow: *.cgi         # don't attempt to access cgi scripts
Disallow: *.gif         # no images
Disallow: *.jpg
Disallow: /images*      # don't index here generally
Disallow: /blackhole/info99*    # these we don't want indexed
Disallow: /blackhole/info8.html # nor this one"],
            ["
User-agent: alfred
Allow: *index.html

User-agent: newchives
Allow: /images/index.html"],
        ];
    }
}
