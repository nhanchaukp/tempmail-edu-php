<?php

namespace Nhanchaukp\TempmailEdu\Http;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Nhanchaukp\TempmailEdu\Config;
use Nhanchaukp\TempmailEdu\Contracts\HttpClientInterface;
use Nhanchaukp\TempmailEdu\Exceptions\ApiException;
use Psr\Http\Message\ResponseInterface;

class GuzzleClient implements HttpClientInterface
{
    private Client $client;
    private ?string $accessToken = null;
    private array $defaultOptions = [];

    public function __construct(private Config $config)
    {
        $this->client = new Client([
            'base_uri' => rtrim($this->config->baseUri, '/') . '/',
            'timeout' => $this->config->timeout,
        ]);

        $this->defaultOptions = [
            'headers' => array_merge(['Accept' => 'application/json'], $this->config->defaultHeaders),
        ];

        // If access token provided in Config use it, otherwise try environment variable.
        // Environment variable name: TEMPMail_ACCESS_TOKEN
        if (!empty($this->config->defaultHeaders['Authorization'])) {
            // If user passed Authorization header in defaultHeaders, honor it.
            $this->accessToken = null; // will be set per headers merging in request()
        } else {
            if (!empty($this->config->defaultHeaders['access_token'] ?? null)) {
                $this->accessToken = (string) $this->config->defaultHeaders['access_token'];
            } else {
                // Try environment variables (support both $_ENV and getenv after loading phpdotenv)
                $envToken = $_ENV['TEMPMail_ACCESS_TOKEN'] ?? getenv('TEMPMail_ACCESS_TOKEN') ?: getenv('TEMPMail_ACCESS_TOKEN');
                if (!empty($envToken)) {
                    $this->accessToken = (string) $envToken;
                }
            }
        }
    }

    public function request(string $method, string $uri, array $options = []): array
    {
        // Merge default options and per-request options
        $merged = array_replace_recursive($this->defaultOptions, $options);

        // If explicit Authorization header provided in merged headers, do not override.
        $headers = $merged['headers'] ?? [];

        if (isset($headers['Authorization'])) {
            // do nothing, user explicitly provided header
        } else {
            // Add Authorization header if access token exists
            if ($this->accessToken !== null) {
                $headers['Authorization'] = 'Bearer ' . $this->accessToken;
            } elseif (!empty($this->config->defaultHeaders['Authorization'])) {
                $headers['Authorization'] = $this->config->defaultHeaders['Authorization'];
            }
            $merged['headers'] = $headers;
        }

        try {
            $response = $this->client->request($method, ltrim($uri, '/'), $merged);
            $body = (string) $response->getBody();
            $decoded = json_decode($body, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new ApiException('Invalid JSON response: ' . json_last_error_msg(), $response->getStatusCode(), $response);
            }

            return $decoded ?? [];
        } catch (GuzzleException $e) {
            $response = $this->extractResponse($e);
            $message = $e->getMessage();
            $code = $e->getCode() ?: 0;
            throw new ApiException($message, $code, $response, $e);
        }
    }

    public function setAccessToken(?string $token): void
    {
        $this->accessToken = $token;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * Try to extract a ResponseInterface from common Guzzle exceptions.
     */
    private function extractResponse(\Throwable $e): ?ResponseInterface
    {
        if (method_exists($e, 'getResponse')) {
            return $e->getResponse();
        }
        return null;
    }
}