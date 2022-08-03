<?php

namespace CViniciusSDias\GoogleCrawler\Proxy\HttpClient;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use CViniciusSDias\GoogleCrawler\Exception\InvalidUrlException;

class DefaultHttpClient implements HttpClientInterface
{
    function __construct(private ClientInterface $client)
    {}

    public function getHttpResponse(string $url): ResponseInterface
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException("Invalid Google URL: $url");
        }

        return $this->client->request('GET', $url);
    }
}
