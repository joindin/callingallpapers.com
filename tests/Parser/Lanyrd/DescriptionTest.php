<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CallingallpapersTest\Parser\Lanyrd;

use Callingallpapers\Parser\Lanyrd\Description;

class DescriptionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider parsingDescriptionProvider
     */
    public function testParsingDescription($file, $expectedDescription)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTMLFile($file);
        $dom->preserveWhiteSpace = false;

        $xpath = new \DOMXPath($dom);

        $parser = new Description();
        $this->assertEquals($expectedDescription, $parser->parse($dom, $xpath));
    }

    public function parsingDescriptionProvider()
    {
        return [
            // ['parsedFile', 'expectedEventUri'],
            [__DIR__ . '/_assets/LanyrdCfp3.html', '<p>You spend a big budget on SEO and Google AdWords, you\'ve attended networking events, invested in trade shows, and even purchased advertising. You get quite some traffic to your website - BUT your conversion rate is still under 1%?<br/>
Myself and Louis Grenier, our guest speaker for today, will show you how you can go back to the office tomorrow and improve your website to expect an increase in sales/enquiries conversion rate.</p>'],
            [__DIR__ . '/_assets/LanyrdCfp4.html', '<p>OSCON celebrates, explains, and demonstrates the power of open source technologies from the inception of languages and frameworks up through their use in the enterprise. We invite you to join us as we bring together a large community of contributors, learners, and users.</p>
<p>Please submit original session and tutorial ideas that share your technology passions. Proposals should include as much detail about the topic and format for the presentation as possible. Detail matters; vague proposals face an uphill climb. Share with us WHO you are, WHY youâre excited about open source, and WHY we should get excited about seeing you speak!</p>
<p>If you are one or more of the following, we invite you to submit a proposal to lead sessions and/or tutorials at OSCON:</p>
<p>Developer or programmer<br/>
Systems administrator<br/>
Hacker or geek<br/>
Enterprise developer or manager<br/>
IT manager, CxO, or entrepreneur<br/>
Trainer or educator<br/>
User experience designer<br/>
Open source enthusiast or activist<br/>
Documentation writers<br/>
Software testers and QA</p>'],
            [__DIR__ . '/_assets/LanyrdCfp5.html', '<p>Open for speakers for the Community Day on Saturday 20 February 2016.</p>
<p>Options are available for Standard 1 hour sessions, 15 minute Lightning Talks or a limited number of 90 minute extended sessions and full-day Pre-Con sessions on the Friday.</p>
<p>The Pre-Con sessions are fully commercial sessions and profit sharing arrangements are in place between the selected presenters and SQL Saturday Melbourne.  These are limited in number and the closing date for Pre-Con submissions is the end of September, 2015.</p>'],
            [__DIR__ . '/_assets/LanyrdCfp6.html', '<p>Got a story youâd like to share with your peers? Weâre looking for people to give 30 minute keynotes, 20 minute presentations, speak on panels and lead 60-90 minute workshops and group activities. Check out and fill out the CFP.</p>'],
            [__DIR__ . '/_assets/LanyrdCfp7.html', ''],
        ];
    }
}
