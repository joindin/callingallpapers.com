<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace CallingallpapersTest\Parser\Lanyrd;

use Callingallpapers\Parser\Lanyrd\Tags;

class TagsTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider parsingTagsProvider
     */
    public function testParsingTags($file, $expectedTags)
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTMLFile($file);
        $dom->preserveWhiteSpace = false;

        $xpath = new \DOMXPath($dom);

        $parser = new Tags();
        $this->assertEquals($expectedTags, $parser->parse($dom, $xpath));
    }

    public function parsingTagsProvider()
    {
        return [
            // ['parsedFile', 'expectedEventUri'],
            [__DIR__ . '/_assets/LanyrdCfp1.html', [
                'Equality and Diversity',
                'Health',
                'Healthcare',
                'justice',
                'Long-Term Care',
                'Prison',
            ]],
            [__DIR__ . '/_assets/LanyrdCfp5.html', [
                'Big Data',
                'Business Intelligence (BI)',
                'DBA',
                'Machine Learning',
                'Parallel Data Warehouse',
                'Power BI',
                'query tuning',
                'SQL Azure',
                'SQL Server',
                'T-SQL',
            ]],
        ];
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testParsingMissingTags()
    {
        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTMLFile(__DIR__ . '/_assets/LanyrdCfp4.html');
        $dom->preserveWhiteSpace = false;

        $xpath = new \DOMXPath($dom);

        $parser = new Tags();
        $parser->parse($dom, $xpath);
    }
}
