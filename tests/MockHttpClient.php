<?php

namespace Nhanchaukp\TempmailEdu\Tests;

use Nhanchaukp\TempmailEdu\Contracts\HttpClientInterface;
use Nhanchaukp\TempmailEdu\Exceptions\ApiException;

class MockHttpClient implements HttpClientInterface
{
    public array $requests = [];
    public ?string $accessToken = null;
    /** @var array<string,array> */
    private array $responses = [];

    public function addResponse(string $method, string $uri, array $response): void
    {
        $key = $this->key($method, $uri);
        $this->responses[$key] = $response;
    }

    public function request(string $method, string $uri, array $options = []): array
    {
        $this->requests[] = ['method' => $method, 'uri' => $uri, 'options' => $options, 'accessToken' => $this->accessToken];
        $key = $this->key($method, $uri);
        if (isset($this->responses[$key])) {
            return $this->responses[$key];
        }
        throw new ApiException("No mock response for {$method} {$uri}");
    }

    public function setAccessToken(?string $token): void
    {
        $this->accessToken = $token;
    }

    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    private function key(string $method, string $uri): string
    {
        return strtoupper($method) . ' ' . $uri;
    }
}