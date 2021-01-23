<?php 
namespace AdinanCenci\InternetRadio\Tool;

class Scraper 
{
    protected $html = '';
    protected $dom  = null;

    public function __construct($html) 
    {
        $this->html = $html;
        $this->dom  = self::loadHtmlDom($this->html);
    }

    public function querySelectorAll($query, $contextNode = null) 
    {
        $xpath = new \DOMXpath($this->dom);
        return $xpath->query($query, $contextNode);
    }

    public function querySelector($query, $contextNode = null) 
    {
        $nodeList = $this->querySelectorAll($query, $contextNode);
        return $nodeList->length ? $nodeList->item(0) : null;
    }

    protected function getCanonicalUrl() 
    {
        $node = $this->querySelector("//link[@rel='canonical']");
        return  $node->getAttribute('href');
    }

    public function saveHtml($domElement) 
    {
        $str = '';
        foreach ($domElement->childNodes as $n) {
            $str .= $this->dom->saveHtml($n);
        }

        return $str;
    }

    public static function loadHtmlDom($html) 
    {
        $dom = new \DOMDocument();
        $dom->strictErrorChecking = false;
        $useInternalErrors        = libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_use_internal_errors($useInternalErrors);

        return $dom;
    }
}
