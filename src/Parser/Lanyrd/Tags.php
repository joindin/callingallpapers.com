<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Callingallpapers\Parser\Lanyrd;

class Tags
{

    public function parse($dom, $xpath)
    {
        $tags = $xpath->query(
            "//ul[contains(@class, \"tags\")]/li/a"
        );
        if (! $tags || $tags->length == 0) {
            throw new \InvalidArgumentException('The CfP does not seem to have tags');
        }

        $returntags = [];
        foreach ($tags as $tag) {
            $returntags[] = trim($tag->textContent);
        }

        return $returntags;
    }
}