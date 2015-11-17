<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CallingallpapersTest\Parser\Lanyrd;

use Callingallpapers\Parser\Lanyrd\EventName;
use Callingallpapers\Parser\Lanyrd\EventUri;

class EventUriTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider parsingEventUriProvider
     */
    public function testParsingEventUri($file, $expectedUri)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTMLFile($file);
        $dom->preserveWhiteSpace = false;

        $xpath = new \DOMXPath($dom);

        $parser = new EventUri();
        $this->assertEquals($expectedUri, $parser->parse($dom, $xpath));
    }

    public function parsingEventUriProvider()
    {
        return [
            // ['parsedFile', 'expectedEventUri'],
            [__DIR__ . '/_assets/LanyrdCfp1.html', '/2016/health-in-justice-conference-equality-in-practic-2/'],
            [__DIR__ . '/_assets/LanyrdCfp2.html', '/2016/witsdc/'],
            [__DIR__ . '/_assets/LanyrdCfp3.html', '/2015/10-easy-fixes-to-increase-your-website-conversio-3/'],
            [__DIR__ . '/_assets/LanyrdCfp4.html', '/2016/oscon/'],
        ];
    }

}
