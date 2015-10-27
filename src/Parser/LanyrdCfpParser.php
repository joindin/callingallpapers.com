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

class LanyrdCfpParser implements ParserInterface
{
    protected $contents = [];

    public function parse()
    {

        $uri = 'http://lanyrd.com/calls/';
       // $uri = 'http://development.local/lanyrd/index.html';

        $contents = new \ArrayObject();

        $dom = new \DOMDocument('1.0', 'UTF-8');
        $dom->loadHTMLFile($uri);
        $dom->preserveWhiteSpace = false;

        $xpath = new \DOMXPath($dom);
        $nodes = $xpath->query("//*[contains(@class, 'call-list-open')]");

        foreach ($nodes as $node) {

            try {
                $cfp = new Cfp();
                /** @var \DOMNode $node */
                $links = $xpath->query('.//a[text()="Call for speakers"]',
                    $node);
                if ($links->length < 1) {
                    continue;
                }

                $dom2 = new \DOMDocument('1.0', 'UTF-8');
                $dom2->loadHTMLFile($links->item(0)->attributes->getNamedItem('href')->textContent);
                $dom2->preserveWhiteSpace = false;

                $xpath2 = new \DOMXPath($dom2);

                $description = $dom2->saveXML($xpath2->query('//div[contains(@class, "description")]')->item(0));

                 $cfp->description = $description;

                $closingDate = $xpath2->query("//span[text()='Closes on:']/following-sibling::strong");
                if ($closingDate && $closingDate->length > 0) {
                    $closingDate = $closingDate->item(0)->textContent;
                    $cfp->dateEnd = new \DateTime($closingDate);
                }

                $cfpUri = $xpath2->query("//div[contains(@class, \"description\")]/following-sibling::p/a");
                if ($cfpUri && $cfpUri->length > 0) {
                    $cfpUri = $cfpUri->item(0)->attributes->getNamedItem('href')->textContent;
                    $cfp->uri = $cfpUri;
                }


                $confPath = $xpath2->query("//h3/a[contains(@class, 'summary')]")->item(0);
                $cfp->conferenceName = $confPath->textContent;
                $cfp->conferenceUri  = 'http://lanyrd.com/' . $confPath->attributes->getNamedItem('href')->textContent;

                $this->contents[] = $cfp;
            } catch (\Exception $e) {}
        }

        return $this->contents;
    }

    public function getContent()
    {
        return $this->contents;
    }


}