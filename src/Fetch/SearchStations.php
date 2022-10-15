<?php 
namespace AdinanCenci\InternetRadio\Fetch;

use \AdinanCenci\InternetRadio\Tool\Scraper;

class SearchStations extends FetchStationsByGenre 
{
    protected $query = null;

    public function __construct(string $query, int $page = 1, string $sortBy = 'featured') 
    {
        $this->query  = $query;
        $this->page   = $page;
        $this->sortBy = $sortBy;
        $this->validateSortBy($sortBy);
        $this->html   = $this->fetchPage();
    }

    protected function fetchPage() : string 
    {
        $url = 'https://www.internet-radio.com/search/?radio=' . $this->query . '&page=/page' . $this->page . '?sortby=' . $this->sortBy;
        return $this->request($url);
    }
}
