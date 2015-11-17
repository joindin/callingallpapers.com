<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Callingallpapers\Writer;

use Callingallpapers\Entity\Cfp;
use Callingallpapers\Parser\ParserInterface;

class DefaultCfpWriter
{

    public function write(ParserInterface $parser)
    {
        $now = (new \DateTime())->format('c');
        $content = [];
        /** @var Cfp $cfp */
        foreach ($parser->getContent() as $cfp) {
            $content[] = array(
                'name' => $cfp->conferenceName,
                'website_uri' => $cfp->conferenceUri,
                'cfp_url' => $cfp->uri,
                'cfp_start_date' => $now,
                'cfp_end_date' => $cfp->dateEnd->format('c'),
                'description' => $cfp->description,
                'tags' => $cfp->tags,
                'start_date' => $cfp->eventStartDate->format('c'),
                'end_date' => $cfp->eventEndDate->format('c'),
                'location' => $cfp->location,
                'latitude' => (float) $cfp->latitude,
                'longitude' => (float)  $cfp->longitude,
            );
        }

        return json_encode($content);
    }
}