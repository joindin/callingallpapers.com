<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Callingallpapers\Parser\Lanyrd;

use Callingallpapers\Entity\Cfp;
use Geocoder\Exception\UnexpectedValue;
use Geocoder\Provider\Nominatim;
use Ivory\HttpAdapter\Configuration;
use Ivory\HttpAdapter\CurlHttpAdapter;

class Entry
{
    /** @var  Cfp */
    protected $cfp;

    public function __construct(Cfp $cfp)
    {
        $this->cfp = $cfp;
    }

    public function parse($uri)
    {
        $cfp = clone($this->cfp);
        try {
            $dom = new \DOMDocument('1.0', 'UTF-8');

            $content = file_Get_contents($uri);
            $content = mb_convert_encoding($content,'UTF-8');
            $dom->loadHTML('<?xml version="1.0" charset="UTF-8" ?>' . $content);
            $dom->preserveWhiteSpace = false;

            $xpath = new \DOMXPath($dom);

            $descriptionParser = new Description();
            $cfp->description = $descriptionParser->parse($dom, $xpath);

            $closingDateParser = new ClosingDate();
            $cfp->dateEnd = $closingDateParser->parse($dom, $xpath);

            $cfpUriParser = new Uri();
            $cfp->uri = $cfpUriParser->parse($dom, $xpath);

            $confNameParser = new EventName();
            $cfp->conferenceName = $confNameParser->parse($dom, $xpath);

            $confUriParser = new EventUri();
            $cfp->conferenceUri  = 'http://lanyrd.com/' . $confUriParser->parse($dom, $xpath);

            $eventStartDate = new EventStartDate();
            $cfp->eventStartDate = $eventStartDate->parse($dom, $xpath);

            try {
                $eventEndDate      = new EventEndDate();
                $cfp->eventEndDate = $eventEndDate->parse($dom, $xpath);
            } catch(\InvalidArgumentException $e) {
                $cfp->eventEndDate = $cfp->eventStartDate;
            }

            $eventLocation = new Location();
            $cfp->location = $eventLocation->parse($dom, $xpath);

            try {
                $location = $this->getLatLonForLocation($cfp->location);
                $cfp->latitude = $location[0];
                $cfp->longitude = $location[1];
            } catch (\UnexpectedValueException $e){
                error_log($e->getMessage());
            }

            try {
                $tags = new Tags();
                $cfp->tags = $tags->parse($dom, $xpath);
            } catch (\InvalidArgumentException $e) {
                $cfp->tags = [];
            }

            return $cfp;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    protected function getLatLonForLocation($location)
    {
        $curl = new CurlHttpAdapter((new Configuration())->setUserAgent(
            'callingallpapers.com - Location to lat/lon-translation - For infos write to andreas@heigl.org'
        ));
        $geocoder = new Nominatim($curl, 'http://nominatim.openstreetmap.org');

        $locations = $geocoder->geocode($location);
        if ($locations->count() > 1) {
        //    throw new \UnexpectedValueException('Too many items found');
        }
        if ($locations->count() < 1) {
            throw new \UnexpectedValueException('Not enough items found');
        }

        $coordinates = $locations->first()->getCoordinates();

        return [$coordinates->getLatitude(), $coordinates->getLongitude()];
    }
}