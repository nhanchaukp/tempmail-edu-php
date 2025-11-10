<?php

namespace Nhanchaukp\TempmailEdu\Resources;

class MailResource
{
    public function __construct(
        public int $id,
        public string $email,
        public string $guest_access_link,
        public ?string $created_at
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['id'] ?? 0),
            email: (string) ($data['email'] ?? ''),
            guest_access_link: (string) ($data['guest_access_link'] ?? ''),
            created_at: $data['created_at'] ?? null
        );
    }
}