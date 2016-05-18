<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CallingallpapersTest\Parser\Lanyrd;

use Callingallpapers\Parser\Lanyrd\Location;

class LocationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider parsingLocationProvider
     */
    public function testParsingLocation($file, $expectedLocation)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTMLFile($file);
        $dom->preserveWhiteSpace = false;

        $xpath = new \DOMXPath($dom);

        $parser = new Location();
        $this->assertEquals($expectedLocation, $parser->parse($dom, $xpath));
    }

    public function parsingLocationProvider()
    {
        return [
            // ['parsedFile', 'expectedLocation'],
            [__DIR__ . '/_assets/LanyrdCfp1.html', 'England, Lincoln'],
            [__DIR__ . '/_assets/LanyrdCfp2.html', 'United States, Washington'],
            [__DIR__ . '/_assets/LanyrdCfp3.html', 'Ireland, Dublin'],
            [__DIR__ . '/_assets/LanyrdCfp4.html', 'United States, Austin'],
            [__DIR__ . '/_assets/LanyrdCfp5.html', 'Australia, Melbourne'],
            [__DIR__ . '/_assets/LanyrdCfp6.html', 'Australia, Sydney'],
            [__DIR__ . '/_assets/LanyrdCfp7.html', 'Germany, Erlangen'],
        ];
    }


}
