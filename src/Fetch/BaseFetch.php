<?php 
namespace AdinanCenci\InternetRadio\Fetch;

use \AdinanCenci\InternetRadio\Tool\Scraper;
use \GuzzleHttp\Client;

abstract class BaseFetch 
{
    protected string $html = '';

    public function __construct() 
    {
        // $this->foo = $bar; ....
        $this->html = $this->fetchPage();
    }

    /**
     * Retrieves the information we want.
     * 
     * @return mixed
     */
    public function fetch() 
    {
        $data = $this->scrape($this->html);
        $this->html = '';
        return $data;
    }

    /**
     * Scrapes the information out of the html.
     * 
     * @param strinc $html
     * @return mixed
     */
    protected function scrape(string $html) 
    {
        $whatWeWant = [];
        $scraper = new Scraper($html);
        // ...
        // ...
        // ...
        return $whatWeWant;
    }

    /**
     * Makes the relevant http request.
     * @return @string Html
     */
    protected function fetchPage() : string
    {
        $url = 'https://www.internet-radio.com/something something';
        return $this->request($url);
    }

    /**
     * Generic method to make http requests.
     * 
     * @param string $url
     * @return string Html
     */
    protected function request(string $url) : string
    {
        $options = [
            'connect_timeout' => 15,
            'timeout' => 15
        ];

        $client = new Client();
        $response = $client->request('GET', $url, $options);

        if ($response->getStatusCode() != 200) {
            throw new \Exception('Error requesting "'.$url.'", code: '.$response->getStatusCode(), 1);
        }

        return $response->getBody();
    }
}
