<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Callingallpapers\Parser\Lanyrd;

class Description
{

    public function parse($dom, $xpath)
    {
        $result = $xpath->query('//div[contains(@class, "description")]');
        if ($result->length < 1) {
            return '';
        }

        $result = $result->item(0)->childNodes;

        if ($result->length <= 0) {
            return '';
        }

        $text = [];
        foreach($result as $node) {
            $text[] = $dom->saveXML($node);
        }
        return trim(implode('', $text));

    }
}