<?php 
namespace AdinanCenci\InternetRadio;

use \AdinanCenci\InternetRadio\Tool\Scraper;
use \AdinanCenci\SimpleRequest\Request;

abstract class Fetch 
{
    protected $html = '';

    public function __construct() 
    {
        $this->html = $this->fetch();
    }

    public function getItems() 
    {
        
    }

    protected function fetch() 
    {
        
    }

    protected function request($url) 
    {
        $r = new Request($url);
        $rsp = $r->request();
        return $rsp->body;
    }    
}
