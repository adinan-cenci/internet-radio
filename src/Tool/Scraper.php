<?php 
namespace AdinanCenci\InternetRadio\Tool;

class Scraper 
{
    protected \DOMDocument $dom;

    public function __construct(string $html) 
    {
        $this->dom = self::loadHtmlDom($html);
    }

    public function querySelectorAll(string $query, \DOMElement $contextNode = null) : \DOMNodeList
    {
        $xpath = new \DOMXpath($this->dom);
        return $xpath->query($query, $contextNode);
    }

    public function querySelector(string $query, \DOMElement $contextNode = null) : ?\DOMNode
    {
        $nodeList = $this->querySelectorAll($query, $contextNode);
        return $nodeList->length ? $nodeList->item(0) : null;
    }

    protected function getCanonicalUrl() : string
    {
        $node = $this->querySelector("//link[@rel='canonical']");
        return $node ? $node->getAttribute('href') : '';
    }

    public function saveHtml(\DOMNode $domElement) : string
    {
        $str = '';
        foreach ($domElement->childNodes as $n) {
            $str .= $this->dom->saveHtml($n);
        }

        return $str;
    }

    public static function loadHtmlDom(string $html) : \DOMDocument
    {
        $dom = new \DOMDocument();
        $dom->strictErrorChecking = false;
        $useInternalErrors        = libxml_use_internal_errors(true);
        $dom->loadHTML($html);
        libxml_use_internal_errors($useInternalErrors);

        return $dom;
    }
}
