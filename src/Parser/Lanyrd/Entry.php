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