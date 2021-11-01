<?php 
namespace AdinanCenci\InternetRadio;

use \AdinanCenci\InternetRadio\Tool\Scraper;

class SearchStations extends FetchStationsByGenre 
{
    protected $query = null;

    public function __construct($query, $page = 1) 
    {
        $this->query = $query;
        $this->page  = $page;
        $this->html  = $this->fetch();
    }

    protected function fetch() 
    {
        return $this->request('https://www.internet-radio.com/search/?radio='.$this->query.'&page=/page'.$this->page);
    }
}
