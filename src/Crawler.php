<?php

namespace CViniciusSDias\GoogleCrawler;

use CViniciusSDias\GoogleCrawler\Proxy\NoProxy;
use CViniciusSDias\GoogleCrawler\Proxy\ProxyInterface;
use Symfony\Component\DomCrawler\Crawler as DomCrawler;

use CViniciusSDias\GoogleCrawler\Proxy\UrlParser\UrlParserInterface;
use CViniciusSDias\GoogleCrawler\Exception\InvalidGoogleHtmlException;
use CViniciusSDias\GoogleCrawler\Proxy\HttpClient\HttpClientInterface;

/**
 * Google Crawler
 *
 * @package CViniciusSDias\GoogleCrawler
 * @author Vinicius Dias
 */
class Crawler
{
    protected HttpClientInterface $client;
    protected UrlParserInterface $urlParser;

    public function __construct(
        ProxyInterface $proxy = new NoProxy()
    ) {
        $this->client = $proxy->httpClient();
        $this->urlParser = $proxy->urlParser();
    }

    /**
     * Returns the 100 first found results for the specified search term
     *
     * @return ResultList
     * @throws \GuzzleHttp\Exception\ServerException If the proxy was overused
     * @throws \GuzzleHttp\Exception\ConnectException If the proxy is unavailable or $countrySpecificSuffix is invalid
     */
    public function getResults(
        SearchTermInterface $searchTerm,
        string $googleDomain = 'google.com',
        string $countryCode = ''
    ): ResultList {
        if (stripos($googleDomain, 'google.') === false || stripos($googleDomain, 'http') === 0) {
            throw new \InvalidArgumentException('Invalid google domain');
        }
        $countryCode = strtoupper($countryCode);

        $googleUrl = "https://$googleDomain/search?q={$searchTerm}&num=100";
        if (!empty($countryCode)) {
            $googleUrl .= "&gl={$countryCode}";
        }

        $response = $this->client->getHttpResponse($googleUrl);
        $stringResponse = (string) $response->getBody();
        $domCrawler = new DomCrawler($stringResponse);
        $googleResultList = $this->createGoogleResultList($domCrawler);
        $resultList = new ResultList($googleResultList->count());

        $domParser = new DomParser($this->urlParser);
        foreach ($googleResultList as $googleResultElement) {
            $domParser
                ->parse($googleResultElement)
                ->select(
                    fn($result) => $resultList->addResult($result)
                );
        }

        return $resultList;
    }

    private function createGoogleResultList(DomCrawler $domCrawler)
    {
        $resultList = $domCrawler->filterXPath('//div[@class="Gx5Zad fP1Qef xpd EtOod pkphOe"]');
        if ($resultList->count() === 0) {
            throw new InvalidGoogleHtmlException('No parseable element found');
        }

        return $resultList;
    }
}
