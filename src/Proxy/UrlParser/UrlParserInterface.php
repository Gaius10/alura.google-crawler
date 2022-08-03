<?php

namespace CViniciusSDias\GoogleCrawler\Proxy\UrlParser;

interface UrlParserInterface
{
    public function parseUrl(string $googleUrl): string;
}
