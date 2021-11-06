<?php 
namespace AdinanCenci\InternetRadio;

use \AdinanCenci\InternetRadio\Tool\Scraper;

class InternetRadio 
{
    public function searchStations($query, $offset = 0, $limit = 20) 
    {
        $page = $this->calcPages($offset, $limit, $lastPage);
        $results = [];

        do {
            $obj      = new SearchStations($query, $page);
            $stations = $obj->getItems();
            $page     += 1;
            $results  = array_merge($results, $stations);
        } while ($page <= $lastPage);

        return array_slice($results, $offset, $limit);
    }

    public function getStationsByGenre($genre, $offset = 0, $limit = 20) 
    {
        $firstPage = $page = $this->calcPages($offset, $limit, $lastPage);
        $results = [];

        do {
            $obj      = new FetchStationsByGenre($genre, $page);
            $stations = $obj->getItems();
            $page     += 1;
            $results  = array_merge($results, $stations);
        } while ($page <= $lastPage);

        return array_slice($results, $offset, $limit);
    }

    public function getGenres() 
    {
        $obj = new FetchGenres();
        return $obj->getItems();
    }

    protected function calcPages(&$offset, &$limit, &$lastPage) 
    {
        $itensPerPage = 20;
        
        $first        = $offset;
        $last         = $offset + $limit;

        $firstPage    = $offset == 0 ? 1 : ceil($first / ($itensPerPage - 1));
        $lastPage     = ceil($last / $itensPerPage);

        $offset       -= ($firstPage - 1) * $itensPerPage;
        
        return $firstPage;
    }
}
