<?php

namespace CViniciusSDias\GoogleCrawler;

use DOMElement;

use Yitznewton\Maybe\Maybe;
use Symfony\Component\DomCrawler\Link;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;
use CViniciusSDias\GoogleCrawler\Proxy\UrlParser\UrlParserInterface;

class DomParser
{
    function __construct(private UrlParserInterface $urlParser)
    {}

    public function parse(DOMElement $result): Maybe
    {
        $resultCrawler = new DomCrawler($result);
        $linkElement = $resultCrawler->filterXPath('//a')->getNode(0);
        if (is_null($linkElement)) {
            return new Maybe(null);
        }

        $resultLink = new Link($linkElement, 'http://google.com/');
        $descriptionElement = $resultCrawler->filterXPath('//div[@class="BNeawe s3v9rd AP7Wnd"]//div[@class="BNeawe s3v9rd AP7Wnd"]')->getNode(0);

        $isImageSuggestion = $resultCrawler->filterXpath('//img')->count() > 0;
        if ($isImageSuggestion) {
            return new Maybe(null);
        }

        if (strpos($resultLink->getUri(), 'http://google.com') === false) {
            return new Maybe(null);
        }

        return new Maybe(
            $this->createResult($resultLink, $descriptionElement)
        );
    }

    private function createResult(Link $resultLink, ?DOMElement $descriptionElement): ?Result
    {
        if (is_null($descriptionElement))
            return null;

        $description = $descriptionElement->nodeValue
            ?? 'A description for this result isn\'t available due to the robots.txt file.';

        $googleResult = new Result();
        $googleResult
            ->setTitle($resultLink->getNode()->nodeValue)
            ->setUrl($this->urlParser->parseUrl($resultLink->getUri()))
            ->setDescription($description);

        return $googleResult;
    }
}
