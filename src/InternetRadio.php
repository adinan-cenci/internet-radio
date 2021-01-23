<?php 
namespace AdinanCenci\InternetRadio;

use \AdinanCenci\InternetRadio\Tool\Scraper;
use \AdinanCenci\SimpleRequest\Request;

class InternetRadio 
{
    public function searchStations($query, $pageOffset = 0, $pageLimit = 1) 
    {
        $pageOffset++;

        $stations = array();

        for ($page = $pageOffset; $page <= $pageLimit; $page++) {
            $obj      = new SearchStations($query, $page);
            $pages    = $obj->countPages();
            $stations = array_merge($stations, $obj->getItems());
        }

        return $stations;
    }

    public function getStationsByGenre($genre, $pageOffset = 0, $pageLimit = 1) 
    {
        $pageOffset++;

        $stations = array();

        for ($page = $pageOffset; $page <= $pageLimit; $page++) {
            $obj      = new FetchStationsByGenre($genre, $page);
            $pages    = $obj->countPages();
            $stations = array_merge($stations, $obj->getItems());
        }

        return $stations;
    }

    public function getGenres() 
    {
        $obj = new FetchGenres();
        return $obj->getItems();
    }
}
