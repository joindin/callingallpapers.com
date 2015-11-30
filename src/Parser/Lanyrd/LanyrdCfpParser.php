<?php
/**
 * Copyright (c) 2015-2015 Andreas Heigl<andreas@heigl.org>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @author    Andreas Heigl<andreas@heigl.org>
 * @copyright 2015-2015 Andreas Heigl/callingallpapers.com
 * @license   http://www.opensource.org/licenses/mit-license.php MIT-License
 * @version   0.0
 * @since     06.03.2012
 * @link      http://github.com/joindin/callingallpapers
 */
namespace Callingallpapers\Parser\Lanyrd;

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