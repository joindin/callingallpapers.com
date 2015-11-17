<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Callingallpapers\Parser\Lanyrd;

class EventStartDate
{

    public function parse($dom, $xpath)
    {
        $startDate = $xpath->query("//div[contains(@class, 'vevent')]/*/abbr[contains(@class, 'dtstart')]"); ///a/abbr[class='dtstart']
        if (! $startDate || $startDate->length == 0) {
            throw new \InvalidArgumentException('The Event does not seem to have a start date');
        }

        $startDate = $startDate->item(0)->attributes->getNamedItem('title')->textContent;

        return new \DateTime($startDate);
    }
}