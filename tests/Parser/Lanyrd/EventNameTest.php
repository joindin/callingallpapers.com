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

class EventNameTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider parsingEventNameProvider
     */
    public function testParsingEventName($file, $expectedName)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTMLFile($file);
        $dom->preserveWhiteSpace = false;

        $xpath = new \DOMXPath($dom);

        $parser = new EventName();
        $this->assertEquals($expectedName, $parser->parse($dom, $xpath));
    }

    public function parsingEventNameProvider()
    {
        return [
            // ['parsedFile', 'expectedEventName'],
            [__DIR__ . '/_assets/LanyrdCfp1.html', 'Health in Justice Conference 2016 - Equality in practice: Managing long term conditions in secure environments'],
            [__DIR__ . '/_assets/LanyrdCfp2.html', 'The Women in Tech Summit - Washington, DC'],
            [__DIR__ . '/_assets/LanyrdCfp3.html', '10 Easy Fixes to Increase Your Website Conversion Rate'],
            [__DIR__ . '/_assets/LanyrdCfp4.html', 'OSCON 2016'],
        ];
    }

}
