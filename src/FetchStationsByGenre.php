<?php 
namespace AdinanCenci\InternetRadio;

use \AdinanCenci\InternetRadio\Tool\Scraper;

class FetchStationsByGenre extends Fetch 
{
    protected $genre = null;
    protected $page  = 1;

    public function __construct($genre, $page = 1) 
    {
        $this->genre = $genre;
        $this->page  = $page;
        $this->html  = $this->fetch();
    }

    public function countPages() 
    {
        $scraper = new Scraper($this->html);
        $lis = $scraper->querySelectorAll("//ul[@class='pagination']/li");

        if (! $lis->length) {
            return 0;
        }

        $last = $lis->item( $lis->length - 2 );

        $a = $scraper->querySelector('a', $last);
        return $a->nodeValue;
    }

    public function getItems() 
    {
        $scraper  = new Scraper($this->html);
        $itens    = $scraper->querySelectorAll("//table[@class='table table-striped']/tbody/tr");
        $stations = array();

        foreach ($itens as $i) {
            $stations[] = $this->parseStation($scraper, $i);
        }

        return $stations;
    }

    protected function parseStation($scraper, $element) 
    {
        $tds          = $scraper->querySelectorAll('./td', $element);
        $secondTd     = $tds->item(1); // player
        $thirdTd      = $tds->item(2); // description
        $fourthTd     = $tds->item(3);
        $id           = '';
        $name         = '';
        $description  = '';
        $playlist     = '';
        $currentlyPlaying = '';
        $bitRate      = '';
        $listeners    = '';
        $homepage     = '';

        //-PLAYLIST--------

        if ($playButton = $scraper->querySelector(".//div[@class='jp-controls']/i", $secondTd)) {
            $match = preg_match('#(http[^\']+)#', $playButton->getAttribute('onclick'), $matches);
            $playlist = $match ? $matches[1] : '';
        }

        //-NAME---------

        if ($header = $scraper->querySelector('./h4', $thirdTd)) {
            $name = $header->firstChild instanceof \DOMElement ? 
                $header->firstChild->nodeValue : 
                $header->nodeValue;

            $id = $header->firstChild instanceof \DOMElement ? 
                trim(str_replace('/station/', '', (string) $header->firstChild->getAttribute('href') ), '/') : 
                '';
        }

        //-PLAYING--------
        
        if ($playingLabel = $scraper->querySelector('./b', $thirdTd)) {
            $currentlyPlaying = $playingLabel->nodeValue;
        }
        
        //-DESCRIPTION--------

        $pastLabel = false;
        $dsc       = array();

        foreach ($thirdTd->childNodes as $c) {

            if ($pastLabel == false && $c instanceof \DOMElement && $c->nodeName == 'a') {
                $homepage = $c->getAttribute('href');
            }
            
            if ($pastLabel == false && $c instanceof \DOMText && substr_count($c->nodeValue, 'Genres') > 0) {
                $pastLabel = true; 
                $dsc[] = $this->strip(ltrim($c->nodeValue, 'Genres: '));
                continue;
            }

            if ($pastLabel && ( $c instanceof \DOMText or $c instanceof \DOMElement) ) {
                $dsc[] = $this->strip($c->nodeValue);
            }
        }

        $dsc = array_filter($dsc, function($d) { return strlen($d); });
        $description = implode(', ', $dsc);

        //-BITRATE--------

        if ($p = $scraper->querySelector('./p', $fourthTd)) {
            $match = preg_match('/([0-9]+) Listeners/', $p->nodeValue, $matches);
            $listeners = $match ? $matches[1] : '';

            $match = preg_match('/([0-9]+) Kbps/', $p->nodeValue, $matches);
            $bitRate = $match ? $matches[1] : '';
        }

        //---------

        $station = array(
            'id'                => $id, 
            'name'              => $name, 
            'description'       => $description, 
            'homepage'          => $homepage, 
            'playlist'          => $playlist, 
            'currentlyPlaying'  => $currentlyPlaying, 
            'listeners'         => $listeners, 
            'bitRate'           => $bitRate
        );

        return array_filter($station, function($v) { return (bool) $v; });
    }

    protected function strip($str) 
    {
        return trim( str_replace(array("\n", "\r", "\t"), '', $str) );
    }

    protected function fetch() 
    {
        return $this->request('https://www.internet-radio.com/stations/'.$this->genre.'/page'.$this->page.'?sortby=listeners');
    }
}
