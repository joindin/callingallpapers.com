<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Callingallpapers\Parser\Lanyrd;

class Uri
{

    public function parse($dom, $xpath)
    {
        $uri = $xpath->query(
            "//div[contains(@class, \"description\")]/following-sibling::p/a"
        );
        if (! $uri || $uri->length == 0) {
            throw new \InvalidArgumentException('The CfP does not seem to have a CfP-Uri');
        }

        return $uri->item(0)->attributes->getNamedItem('href')->textContent;
    }
}