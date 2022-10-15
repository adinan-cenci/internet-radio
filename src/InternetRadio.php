<?php 
namespace AdinanCenci\InternetRadio;

use \AdinanCenci\InternetRadio\Fetch\FetchGenres;
use \AdinanCenci\InternetRadio\Fetch\FetchStationsByGenre;
use \AdinanCenci\InternetRadio\Fetch\SearchStations;

class InternetRadio 
{
    /**
     * @param string $query
     * @param int $offset
     * @param int $limit
     * @param string $sortBy Possible values: 'featured', 'listeners', 'bitrate'
     * @return array[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function searchStations(string $query, int $offset = 0, int $limit = 20, $sortBy = 'featured') : array
    {
        $page = $this->calcPages($offset, $limit, $lastPage);
        $results = [];

        do {
            $obj      = new SearchStations($query, $page, $sortBy);
            $stations = $obj->fetch();
            $page     += 1;
            $results  = array_merge($results, $stations);
        } while ($page <= $lastPage && $stations);

        return array_slice($results, $offset, $limit);
    }

    /**
     * @param string $genre
     * @param int $offset
     * @param int $limit
     * @param string $sortBy Possible values: 'featured', 'listeners', 'bitrate'
     * @return array[]
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function getStationsByGenre(string $genre, int $offset = 0, int $limit = 20, $sortBy = 'featured') : array
    {
        $firstPage = $page = $this->calcPages($offset, $limit, $lastPage);
        $results = [];

        do {
            $obj      = new FetchStationsByGenre($genre, $page, $sortBy);
            $stations = $obj->fetch();
            $page     += 1;
            $results  = array_merge($results, $stations);
        } while ($page <= $lastPage && $stations);

        return array_slice($results, $offset, $limit);
    }

    /**
     * @return string[]
     * @throws \RuntimeException
     */
    public function getGenres() : array
    {
        $obj = new FetchGenres();
        return $obj->fetch();
    }

    /**
     * A helper function to tell us to which page we start and stop making
     * request to.
     */
    protected function calcPages(&$offset, &$limit, &$lastPage) 
    {
        // The site displays 20 itens per page
        $itensPerPage = 20;

        $first        = $offset;
        $last         = $offset + $limit;

        $firstPage    = $offset == 0 ? 1 : ceil($first / ($itensPerPage - 1));
        $lastPage     = ceil($last / $itensPerPage);

        $offset       -= ($firstPage - 1) * $itensPerPage;

        return $firstPage;
    }
}
