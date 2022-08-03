<?php

namespace CViniciusSDias\GoogleCrawler\Proxy\HttpClient;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;

class KHttpClient implements HttpClientInterface
{
    private string $endpoint;

    public function __construct(
        private ClientInterface $client,
        private int $serverNumber
    ) {
        if ($serverNumber > 9 || $serverNumber < 1) {
            throw new \InvalidArgumentException('Invalid server number');
        }

        $this->endpoint = "http://server{$serverNumber}.kproxy.com";
    }

    public function getHttpResponse(string $url): ResponseInterface
    {
        $this
            ->client
            ->request('GET', "{$this->endpoint}/index.jsp");

        $this->sendRequestToProxy($url);

        $parsedUrl = parse_url($url);
        $queryString = $parsedUrl['query'];
        $actualUrl = "{$this->endpoint}/servlet/redirect.srv/swh/suxm/sqyudex/spqr/p1/search?{$queryString}";

        return $this->client->request('GET', $actualUrl);
    }


    private function sendRequestToProxy(string $url): void
    {
        $encodedUrl = urlencode($url);
        $postData = ['page' => $encodedUrl, 'x' => 0, 'y' => 0];
        $headers = [
            'Accept' => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8'
        ];
        $this->client->request(
            'POST',
            "{$this->endpoint}/doproxy.jsp",
            ['form_params' => $postData, 'headers' => $headers]
        );
    }
}
