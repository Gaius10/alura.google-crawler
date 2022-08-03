<?php

namespace CViniciusSDias\GoogleCrawler\Proxy\HttpClient;

use GuzzleHttp\ClientInterface;
use Psr\Http\Message\ResponseInterface;
use CViniciusSDias\GoogleCrawler\Exception\InvalidUrlException;
use CViniciusSDias\GoogleCrawler\Proxy\HttpClient\HttpClientInterface;

class CommonHttpClient implements HttpClientInterface
{
    public function __construct(
        private string $endpoint,
        private ClientInterface $client
    ) {
        if (!filter_var($endpoint, FILTER_VALIDATE_URL)) {
            throw new InvalidUrlException("Invalid CommonProxy endpoint: $endpoint");
        }

        $this->endpoint = $endpoint;
    }

    /** {@inheritdoc} */
    public function getHttpResponse(string $url): ResponseInterface
    {
        $data = ['u' => $url, 'allowCookies' => 'on'];

        $response = $this->client->request(
            'POST',
            $this->endpoint,
            [ 'form_params' => $data ]
        );

        return $response;
    }
}
