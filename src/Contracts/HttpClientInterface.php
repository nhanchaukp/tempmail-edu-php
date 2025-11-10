<?php

namespace Nhanchaukp\TempmailEdu\Contracts;

interface HttpClientInterface
{
    /**
     * Send HTTP request and return decoded JSON as array.
     *
     * @param string $method HTTP method (GET, POST, ...)
     * @param string $uri Relative URI (e.g. /auth/login)
     * @param array $options Options (query, json, headers, etc.)
     * @return array Decoded JSON response
     *
     * @throws \Nhanchaukp\TempmailEdu\Exceptions\ApiException on errors
     */
    public function request(string $method, string $uri, array $options = []): array;

    /**
     * Set the access token (e.g. Bearer token) to be used for subsequent requests.
     */
    public function setAccessToken(?string $token): void;

    /**
     * Get current access token if set.
     */
    public function getAccessToken(): ?string;
}