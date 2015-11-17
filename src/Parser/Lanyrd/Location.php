<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Callingallpapers\Parser\Lanyrd;

class Location
{

    public function parse($dom, $xpath)
    {
        $locations = $xpath->query("//div[contains(@class, 'vevent')]/p[contains(@class, 'location')]/a"); ///a/abbr[class='dtstart']
        if (! $locations || $locations->length == 0) {
            throw new \InvalidArgumentException('The Event does not seem to have an end date');
        }
        $location = [];
        foreach($locations as $item) {
            $location[] = trim($item->textContent);
        }

        return implode(', ', array_unique($location));
    }
}