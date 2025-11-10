<?php

namespace Nhanchaukp\TempmailEdu\Contracts;

interface TempmailEduInterface
{
    public function login(string $email, string $password): array;

    public function getUser(): array;

    public function getDomains(): array;

    public function getEmails(): array;

    public function createEmail(array $payload): array;

    public function getEmailMessages(int $id): array;

    public function getMessagesByEmail(string $email): array;

    public function deleteEmails(array $emails): array;

    public function getMessage(int $id): array;

    public function getNetflixCode(string $email): array;
}