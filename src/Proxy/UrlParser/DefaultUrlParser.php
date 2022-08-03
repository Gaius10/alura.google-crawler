<?php

namespace CViniciusSDias\GoogleCrawler\Proxy\UrlParser;

use CViniciusSDias\GoogleCrawler\Exception\InvalidResultException;

class DefaultUrlParser implements UrlParserInterface
{
    public function parseUrl(string $googleUrl): string
    {
        $parsedUrl = parse_url($googleUrl);
        parse_str($parsedUrl['query'], $queryStringParams);

        $targetUrl = filter_var($queryStringParams['q'], FILTER_VALIDATE_URL);
        if (!$targetUrl) {
            throw new InvalidResultException();
        }

        return $targetUrl;
    }
}
