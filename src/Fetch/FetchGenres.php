<?php 
namespace AdinanCenci\InternetRadio\Fetch;

use \AdinanCenci\InternetRadio\Tool\Scraper;

/**
 * Retrieves the list of musical genres internet-radio uses to categorize
 * its stations.
 *
 * @return string[]
 */
class FetchGenres extends BaseFetch 
{
    /**
     * Return the musical genres.
     *
     * @return string[]
     */
    protected function scrape(string $html)
    {
        $genres  = [];
        $scraper = new Scraper($html);
        $itens   = $scraper->querySelectorAll('//dl/dt/a');

        foreach ($itens as $i) {
            $genres[] = $i->nodeValue;
        }

        unset($scraper);

        return $genres;
    }

    protected function fetchPage() : string 
    {
        $url = 'https://www.internet-radio.com/stations/';
        return $this->request($url);
    }
}
