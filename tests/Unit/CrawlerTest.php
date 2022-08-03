<?php
namespace CViniciusSDias\GoogleCrawler\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\ResponseInterface;
use CViniciusSDias\GoogleCrawler\Crawler;
use CViniciusSDias\GoogleCrawler\SearchTerm;
use CViniciusSDias\GoogleCrawler\Proxy\NoProxy;
use CViniciusSDias\GoogleCrawler\SearchTermInterface;
use CViniciusSDias\GoogleCrawler\Proxy\ProxyInterface;
use CViniciusSDias\GoogleCrawler\Exception\InvalidGoogleHtmlException;
use CViniciusSDias\GoogleCrawler\Proxy\HttpClient\HttpClientInterface;
use CViniciusSDias\GoogleCrawler\Proxy\UrlParser\UrlParserInterface;

class CrawlerTest extends TestCase
{
    private Crawler $crawler;

    public function __construct(...$args)
    {
        $this->crawler = new Crawler(new NoProxy());
        parent::__construct(...$args);
    }

    public function testTryingToGetResultsWithoutSslMustFail()
    {
        $this->expectException(\InvalidArgumentException::class);
        $domain = 'http://google.com'; // https is required
        $this->crawler->getResults(new SearchTerm(''), $domain);
    }

    public function testTryingToGetResultsOutOfGoogleMustFail()
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->crawler->getResults(new SearchTerm(''), 'invalid-domain.com');
    }

    public function testTryingToParseInvalidHtmlMustThrowException()
    {
        $this->expectException(InvalidGoogleHtmlException::class);
        $streamMock = $this->createMock(StreamInterface::class);
        $streamMock->method('__toString')
            ->willReturn('<html><head></head><body>Invalid HTML</body></html>');

        $responseMock = $this->createMock(ResponseInterface::class);
        $responseMock->method('getBody')
            ->willReturn($streamMock);

        $urlParserMock = $this->createMock(UrlParserInterface::class);
        $urlParserMock->method('parseUrl')
            ->willReturn('https://example.net');

        $proxyMock = $this->createMock(ProxyInterface::class);
        $proxyMock->method('urlParser')
            ->willReturn($urlParserMock);

        $httpClientMock = $this->createMock(HttpClientInterface::class);
        $httpClientMock->method('getHttpResponse')
            ->willReturn($responseMock);

        $proxyMock->method('httpClient')
            ->willReturn($httpClientMock);

        $searchTermMock = $this->createMock(SearchTermInterface::class);
        $searchTermMock
            ->method('__toString')
            ->willReturn('');

        $crawler = new Crawler($proxyMock);
        $crawler->getResults($searchTermMock);
    }
}
