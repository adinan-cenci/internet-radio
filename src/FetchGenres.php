<?php 
namespace AdinanCenci\InternetRadio;

use \AdinanCenci\InternetRadio\Tool\Scraper;

class FetchGenres extends Fetch 
{
    public function __construct() 
    {
        $this->html = $this->fetch();
    }

    public function getItems() 
    {
        $scraper = new Scraper($this->html);
        $itens   = $scraper->querySelectorAll('//dl/dt/a');
        $genres  = array();

        foreach ($itens as $i) {
            $genres[] = $i->nodeValue;
        }

        unset($scraper);
        $this->html = '';

        return $genres;
    }

    protected function fetch() 
    {
        return $this->request('https://www.internet-radio.com/stations/');
    }
}
