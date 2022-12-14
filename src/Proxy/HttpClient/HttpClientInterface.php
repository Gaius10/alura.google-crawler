<?php

namespace CViniciusSDias\GoogleCrawler\Proxy\HttpClient;

use Psr\Http\Message\ResponseInterface;

interface HttpClientInterface
{
    public function getHttpResponse(string $url): ResponseInterface;
}
