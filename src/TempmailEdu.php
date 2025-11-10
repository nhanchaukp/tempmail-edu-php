<?php

namespace Nhanchaukp\TempmailEdu;

use Nhanchaukp\TempmailEdu\Contracts\HttpClientInterface;
use Nhanchaukp\TempmailEdu\Contracts\TempmailEduInterface;
use Nhanchaukp\TempmailEdu\Exceptions\ApiException;
use Nhanchaukp\TempmailEdu\Resources\DomainResource;
use Nhanchaukp\TempmailEdu\Resources\MailResource;
use Nhanchaukp\TempmailEdu\Resources\MessageResource;
use Nhanchaukp\TempmailEdu\Resources\NetflixCodeResource;
use Nhanchaukp\TempmailEdu\Resources\UserResource;

class TempmailEdu implements TempmailEduInterface
{
    public function __construct(private HttpClientInterface $http)
    {
    }

    /**
     * Set Authorization Bearer token for the http client.
     */
    public function setAccessToken(?string $token): void
    {
        $this->http->setAccessToken($token);
    }

    public function login(string $email, string $password): array
    {
        $payload = ['json' => ['email' => $email, 'password' => $password]];
        $resp = $this->http->request('POST', '/auth/login', $payload);

        // If login returns an access_token in data, set it automatically
        if (!empty($resp['data']) && is_array($resp['data']) && !empty($resp['data']['access_token'])) {
            $this->setAccessToken((string) $resp['data']['access_token']);
        }

        return $resp;
    }

    public function getUser(): array
    {
        $resp = $this->http->request('GET', '/user');
        if (!empty($resp['data']) && is_array($resp['data'])) {
            $resp['data'] = UserResource::fromArray($resp['data']);
        }
        return $resp;
    }

    public function getDomains(): array
    {
        $resp = $this->http->request('GET', '/domain');
        if (!empty($resp['data']) && is_array($resp['data'])) {
            $resp['data'] = array_map(fn($d) => DomainResource::fromArray($d), $resp['data']);
        }
        return $resp;
    }

    public function getEmails(): array
    {
        $resp = $this->http->request('GET', '/email');
        if (!empty($resp['data']) && is_array($resp['data'])) {
            $resp['data'] = array_map(fn($m) => MailResource::fromArray($m), $resp['data']);
        }
        return $resp;
    }

    public function createEmail(array $payload): array
    {
        $options = ['json' => $payload];
        $resp = $this->http->request('POST', '/email/create', $options);
        if (!empty($resp['data']) && is_array($resp['data'])) {
            $resp['data'] = MailResource::fromArray($resp['data']);
        }
        return $resp;
    }

    public function getEmailMessages(int $id): array
    {
        $resp = $this->http->request('GET', "/email/{$id}");
        if (!empty($resp['data']) && is_array($resp['data'])) {
            $resp['data'] = array_map(fn($m) => MessageResource::fromArray($m), $resp['data']);
        }
        return $resp;
    }

    public function getMessagesByEmail(string $email): array
    {
        $resp = $this->http->request('GET', "/email/query/" . rawurlencode($email));
        if (!empty($resp['data']) && is_array($resp['data'])) {
            $resp['data'] = array_map(fn($m) => MessageResource::fromArray($m), $resp['data']);
        }
        return $resp;
    }

    public function deleteEmails(array $emails): array
    {
        if (empty($emails)) {
            throw new ApiException('emails array must not be empty');
        }
        $options = ['json' => ['emails' => $emails]];
        $resp = $this->http->request('POST', '/email/delete', $options);
        return $resp;
    }

    public function getMessage(int $id): array
    {
        $resp = $this->http->request('GET', "/message/{$id}");
        if (!empty($resp['data']) && is_array($resp['data'])) {
            $resp['data'] = MessageResource::fromArray($resp['data']);
        }
        return $resp;
    }

    public function getNetflixCode(string $email): array
    {
        $options = ['json' => ['email' => $email]];
        $resp = $this->http->request('POST', '/netflix/get-code', $options);
        if (!empty($resp['data']) && is_array($resp['data'])) {
            $resp['data'] = NetflixCodeResource::fromArray($resp['data']);
        }
        return $resp;
    }
}