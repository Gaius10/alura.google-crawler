<?php

namespace CViniciusSDias\GoogleCrawler\Proxy;

use CViniciusSDias\GoogleCrawler\Proxy\UrlParser\CommonUrlParser;
use CViniciusSDias\GoogleCrawler\Proxy\HttpClient\CommonHttpClient;
use GuzzleHttp\Client;

class CommonProxy implements ProxyInterface
{
    private CommonHttpClient $client;
    private CommonUrlParser $parser;

    function __construct()
    {
        $guzzle = new Client([
            'cookies' => true,
            'verify' => false
        ]);

        $this->client = new CommonHttpClient('https://google.com', $guzzle);
        $this->parser = new CommonUrlParser();
    }

    public function httpClient(): CommonHttpClient
    {
        return $this->client;
    }

    function urlParser(): CommonUrlParser
    {
        return $this->parser;
    }

}
