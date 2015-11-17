<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CallingallpapersTest\Parser\Lanyrd;

use Callingallpapers\Parser\Lanyrd\EventEndDate;

class EventEndDateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider parsingEventEndDateProvider
     */
    public function testParsingEventEndDate($file, $expectedEventEndDate)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTMLFile($file);
        $dom->preserveWhiteSpace = false;

        $xpath = new \DOMXPath($dom);

        $parser = new EventEndDate();
        $this->assertEquals($expectedEventEndDate, $parser->parse($dom, $xpath));
    }

    public function parsingeventEndDateProvider()
    {
        return [
            // ['parsedFile', 'expectedEventUri'],
            [__DIR__ . '/_assets/LanyrdCfp4.html', new \DateTime('2016-05-19T00:00:00+00:00')],
            [__DIR__ . '/_assets/LanyrdCfp5.html', new \DateTime('2016-02-20T00:00:00+00:00')],
            [__DIR__ . '/_assets/LanyrdCfp7.html', new \DateTime('2016-03-09T00:00:00+00:00')],
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testPArsingMissingEndDate()
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTMLFile(__DIR__ . '/_assets/LanyrdCfp3.html');
        $dom->preserveWhiteSpace = false;

        $xpath = new \DOMXPath($dom);

        $parser = new EventEndDate();
        $parser->parse($dom, $xpath);
    }

}
