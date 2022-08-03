<?php

namespace CViniciusSDias\GoogleCrawler\Proxy;

use GuzzleHttp\Client;
use CViniciusSDias\GoogleCrawler\Proxy\UrlParser\KUrlParser;
use CViniciusSDias\GoogleCrawler\Proxy\HttpClient\KHttpClient;

class KProxy implements ProxyInterface
{
    private KUrlParser $parser;
    private KHttpClient $client;

    function __construct()
    {
        $this->parser = new KUrlParser();
        $this->client = new KHttpClient(new Client(), 1);
    }

    public function httpClient(): KHttpClient
    {
        return $this->client;
    }

    public function urlParser(): KUrlParser
    {
        return $this->parser;
    }
}
