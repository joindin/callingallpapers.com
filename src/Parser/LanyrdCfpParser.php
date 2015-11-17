<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Callingallpapers\Parser;

use Callingallpapers\Entity\Cfp;
use Callingallpapers\Parser\Lanyrd\Entry;
use Callingallpapers\Parser\Lanyrd\LanyrdEntryParser;

class LanyrdCfpParser implements ParserInterface
{
    protected $contents = [];

    public function parse()
    {

        $uri = 'http://lanyrd.com/calls/';
        $i = 0;

        $pages = 0;
       // $uri = 'http://development.local/lanyrd/index.html';

        do {

            $contents = new \ArrayObject();

            $dom = new \DOMDocument('1.0', 'UTF-8');
            $dom->loadHTMLFile($uri . '?page=' . ($i+1));
            $dom->preserveWhiteSpace = false;

            $xpath = new \DOMXPath($dom);
            $nodes = $xpath->query("//div[@class='pagination']/ol/li[last()]/a");
            if ($nodes->length < 1) {
                continue;
            }
            $pages = $nodes[0]->textContent;

            $xpath = new \DOMXPath($dom);
            $nodes = $xpath->query("//*[contains(@class, 'call-list-open')]");

            $cfp = new Cfp();
            $lanyrdEntryParser = new Entry($cfp);
            foreach ($nodes as $node) {
                try {
                    /** @var \DOMNode $node */
                    $links = $xpath->query('.//a[text()="Call for speakers"]',
                        $node);
                    if ($links->length < 1) {
                        continue;
                    }

                    $eventPageUrl = $links->item(0)->attributes->getNamedItem('href')->textContent;
                    error_log($eventPageUrl);
                    if (! $eventPageUrl) {
                        continue;
                    }
                    $this->contents[] = $lanyrdEntryParser->parse($eventPageUrl);
                } catch (\Exception $e) {
                    error_log($e->getMEssage());
                }
            }
        } while (++$i < $pages);

        return $this->contents;
    }

    public function getContent()
    {
        return $this->contents;
    }


}