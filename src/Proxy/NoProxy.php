<?php

namespace CViniciusSDias\GoogleCrawler\Proxy;

use CViniciusSDias\GoogleCrawler\Proxy\HttpClient\DefaultHttpClient;
use CViniciusSDias\GoogleCrawler\Proxy\UrlParser\DefaultUrlParser;
use GuzzleHttp\Client;

class NoProxy implements ProxyInterface
{
    private DefaultUrlParser $parser;
    private DefaultHttpClient $client;

    function __construct()
    {
        $this->parser = new DefaultUrlParser();
        $this->client = new DefaultHttpClient(new Client());
    }

    public function httpClient(): DefaultHttpClient
    {
        return $this->client;
    }

    public function urlParser(): DefaultUrlParser
    {
        return $this->parser;
    }
}
