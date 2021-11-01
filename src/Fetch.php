<?php 
namespace AdinanCenci\InternetRadio;

use \AdinanCenci\InternetRadio\Tool\Scraper;
use \GuzzleHttp\Client;

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
        $client = new Client();
        $response = $client->request('GET', $url, ['connect_timeout' => 15, 'timeout' => 15]);

        if ($response->getStatusCode() != 200) {
            throw new \Exception('Error requesting "'.$url.'", code: '.$response->getStatusCode(), 1);
        }

        return $response->getBody();
    }    
}
