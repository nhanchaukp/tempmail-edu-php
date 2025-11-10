<?php

namespace Nhanchaukp\TempmailEdu\Tests;

use Nhanchaukp\TempmailEdu\TempmailEdu;
use PHPUnit\Framework\TestCase;

class TempmailEduTest extends TestCase
{
    public function testLoginSetsAccessToken()
    {
        $mock = new MockHttpClient();
        $mock->addResponse('POST', '/auth/login', [
            'success' => true,
            'message' => 'OK',
            'data' => [
                'access_token' => 'test-token-123',
                'token_type' => 'Bearer',
                'user' => null
            ]
        ]);

        $client = new TempmailEdu($mock);
        $resp = $client->login('user@example.com', 'pass');

        $this->assertArrayHasKey('data', $resp);
        $this->assertEquals('test-token-123', $mock->getAccessToken());
    }

    public function testCreateEmailSendsCorrectPayload()
    {
        $mock = new MockHttpClient();
        $payload = [
            'user' => 'guest',
            'domain' => 'tempmail.id.vn',
            'generate_guest_link' => true,
            'guest_link_expiration_days' => 7
        ];

        $mock->addResponse('POST', '/email/create', [
            'success' => true,
            'message' => 'created',
            'data' => [
                'id' => 1,
                'email' => 'guest@tempmail.id.vn',
                'guest_access_link' => 'https://...'
            ]
        ]);

        $client = new TempmailEdu($mock);
        $resp = $client->createEmail($payload);

        // ensure request recorded
        $this->assertNotEmpty($mock->requests);
        $last = end($mock->requests);
        $this->assertEquals('POST', $last['method']);
        $this->assertStringContainsString('/email/create', $last['uri']);
        $this->assertArrayHasKey('json', $last['options']);
        $this->assertEquals($payload, $last['options']['json']);
    }
}