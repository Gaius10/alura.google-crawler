<?php

namespace CViniciusSDias\GoogleCrawler\Proxy;

use CViniciusSDias\GoogleCrawler\Proxy\UrlParser\UrlParserInterface;
use CViniciusSDias\GoogleCrawler\Proxy\HttpClient\HttpClientInterface;

interface ProxyInterface
{
    public function httpClient(): HttpClientInterface;
    public function urlParser(): UrlParserInterface;
}
