<?php 
namespace AdinanCenci\InternetRadio\Fetch;

use \AdinanCenci\InternetRadio\Tool\Scraper;

/**
 * Retrieves a paginated list of radio stations based on musical genre.
 */
class FetchStationsByGenre extends BaseFetch 
{
    protected string $genre = '';
    protected int $page = 1;
    protected string $sortBy = 'featured';

    public function __construct(string $genre, int $page = 1, string $sortBy = 'featured') 
    {
        $this->genre  = $genre;
        $this->page   = $page;
        $this->sortBy = $sortBy;
        $this->validateSortBy($sortBy);
        $this->html   = $this->fetchPage();
    }

    /**
     * Return a list of radio stations.
     * 
     * @return array[]
     */
    public function fetch() : array
    {
        return $this->scrapeStations($this->html);
    }

    /**
     * Return the number of pages of radio stations indexed with this musical
     * genre.
     */
    public function fetchNumberOfPages() : int
    {
        return $this->scrapeStations($this->html);
    }

    protected function scrapeNumberOfPages(string $html) : int
    {
        $scraper   = new Scraper($html);
        $html      = '';
        $listItems = $scraper->querySelectorAll("//ul[@class='pagination']/li");

        if (! $listItems->length) {
            return 0;
        }

        // Account for the " » " anchor.
        $last = $listItems->item($listItems->length - 2);

        $anchor = $scraper->querySelector('a', $last);
        return (int) $anchor->nodeValue;
    }

    public function scrapeStations(string $html) : array
    {
        $scraper  = new Scraper($html);
        $html     = '';
        $itens    = $scraper->querySelectorAll("//table[@class='table table-striped']/tbody/tr");
        $stations = array();

        foreach ($itens as $i) {
            $stations[] = $this->scrapeStation($scraper, $i);
        }

        return $stations;
    }

    protected function scrapeStation(Scraper $scraper, \DOMElement $tableRow) : array
    {
        $tds          = $scraper->querySelectorAll('./td', $tableRow);
        $secondTd     = $tds->item(1); // player
        $thirdTd      = $tds->item(2); // description
        $fourthTd     = $tds->item(3); // stats

        $station = array(
            'id'                => $this->scrapeId($scraper, $thirdTd), 
            'name'              => $this->scrapeName($scraper, $thirdTd), 
            'description'       => $this->scrapeDescription($scraper, $thirdTd), 
            'homepage'          => $this->scrapeHomepage($scraper, $thirdTd), 
            'playlist'          => $this->scrapePlaylist($scraper, $secondTd), 
            'currentlyPlaying'  => $this->scrapeCurrentlyPlaying($scraper, $thirdTd), 
            'listeners'         => $this->scrapeListeners($scraper, $fourthTd), 
            'bitRate'           => $this->scrapeBitRate($scraper, $fourthTd)
        );

        return array_filter($station, function($v) { return (bool) $v; });
    }

    protected function scrapeId(Scraper $scraper, $contextNode) : string 
    {
        $header = $scraper->querySelector('./h4', $contextNode);

        if (! $header) {
            return '';
        }

        return $header->firstChild instanceof \DOMElement
            ? trim(str_replace('/station/', '', (string) $header->firstChild->getAttribute('href') ), '/')
            : '';
    }

    protected function scrapeName(Scraper $scraper, $contextNode) : string 
    {
        $header = $scraper->querySelector('./h4', $contextNode);

        if (! $header) {
            return '';
        }

        return $header->firstChild instanceof \DOMElement 
            ? $header->firstChild->nodeValue
            : $header->nodeValue;
    }

    protected function scrapeDescription(Scraper $straper, $contextNode) : string 
    {
        $dsc       = [];
        $pastLabel = false;

        foreach ($contextNode->childNodes as $c) {
            if ($pastLabel == false && $c instanceof \DOMText && substr_count($c->nodeValue, 'Genres') > 0) {
                $pastLabel = true;
                $dsc[] = $this->stripControlCharacters(ltrim($c->nodeValue, 'Genres:'));
                continue;
            }

            if ($pastLabel && ( $c instanceof \DOMText or $c instanceof \DOMElement) ) {
                $dsc[] = $this->stripControlCharacters($c->nodeValue);
            }
        }

        $dsc = array_filter($dsc, function($d) { return (bool) strlen($d); });
        return implode(', ', $dsc);
    }

    protected function scrapeHomepage(Scraper $straper, $contextNode) : string 
    {
        foreach ($contextNode->childNodes as $c) {
            if ($c instanceof \DOMElement && $c->nodeName == 'a') {
                return $c->getAttribute('href');
            }
        }

        return '';
    }

    protected function scrapePlaylist(Scraper $scraper, \DOMElement $contextNode) : string 
    {
        if ($playButton = $scraper->querySelector(".//div[@class='jp-controls']/i", $contextNode)) {
            $match = preg_match('#(http[^\']+)#', $playButton->getAttribute('onclick'), $matches);
            return $match ? $matches[1] : '';
        }

        if ($playButton = $scraper->querySelector("./a", $contextNode)) {
            $match = preg_match('#(http[^\']+)\'\)#', $playButton->getAttribute('onclick'), $matches);
            return $match ? $matches[1] : '';
        }

        return '';
    }

    protected function scrapeCurrentlyPlaying(Scraper $scraper, \DOMElement $contextNode) : string 
    {
        $playingLabel = $scraper->querySelector('./b', $contextNode);

        return $playingLabel 
            ? $playingLabel->nodeValue
            : '';
    }

    protected function scrapeListeners(Scraper $scraper, \DOMElement $contextNode) : string 
    {
        if (! $p = $scraper->querySelector('./p', $contextNode)) {
            return '';
        }
        
        $match = preg_match('/([0-9]+) Listeners/', $p->nodeValue, $matches);
        return $match ? $matches[1] : '';
    }

    protected function scrapeBitRate(Scraper $scraper, \DOMElement $contextNode) : string 
    {
        if (! $p = $scraper->querySelector('./p', $contextNode)) {
            return '';
        }

        $match = preg_match('/([0-9]+) Kbps/', $p->nodeValue, $matches);
        return $match ? $matches[1] : '';
    }

    protected function stripControlCharacters(string $str) : string 
    {
        return trim( str_replace(["\n", "\r", "\t"], '', $str) );
    }

    protected function fetchPage() : string 
    {
        $url = 'https://www.internet-radio.com/stations/' . $this->genre . '/page' . $this->page . '?sortby=' . $this->sortBy;
        return $this->request($url);
    }

    protected function validateSortBy(string $sortBy) 
    {
        if (! in_array($sortBy, ['featured', 'listeners', 'bitrate'])) {
            throw new \InvalidArgumentException('Unrecognized sortby parameter: ' . $sortBy);
        }

        return true;
    }
}
