<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CallingallpapersTest\Parser\Lanyrd;

use Callingallpapers\Parser\Lanyrd\EventStartDate;

class EventStartDateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider parsingEventStartDateProvider
     */
    public function testParsingEventStartDate($file, $expectedEventStartDate)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTMLFile($file);
        $dom->preserveWhiteSpace = false;

        $xpath = new \DOMXPath($dom);

        $parser = new EventStartDate();
        $this->assertEquals($expectedEventStartDate, $parser->parse($dom, $xpath));
    }

    public function parsingeventStartDateProvider()
    {
        return [
            // ['parsedFile', 'expectedEventUri'],
            [__DIR__ . '/_assets/LanyrdCfp3.html', new \DateTime('2015-11-18T00:00:00+00:00')],
            [__DIR__ . '/_assets/LanyrdCfp4.html', new \DateTime('2016-05-16T00:00:00+00:00')],
            [__DIR__ . '/_assets/LanyrdCfp5.html', new \DateTime('2016-02-19T00:00:00+00:00')],
            [__DIR__ . '/_assets/LanyrdCfp6.html', new \DateTime('2015-12-09T00:00:00+00:00')],
            [__DIR__ . '/_assets/LanyrdCfp7.html', new \DateTime('2016-03-07T00:00:00+00:00')],
        ];
    }

}
