<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Callingallpapers\Parser\Lanyrd;

class ClosingDate
{

    public function parse($dom, $xpath)
    {
        $closingDate = $xpath->query("//span[text()='Closes on:']/following-sibling::strong");
        if (! $closingDate || $closingDate->length == 0) {
            throw new \InvalidArgumentException('The CfP does not seem to have a closing date');
        }

        $closingDate = $closingDate->item(0)->textContent;

        return new \DateTime($closingDate);
    }
}