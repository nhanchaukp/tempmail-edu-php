<?php

namespace Nhanchaukp\TempmailEdu\Resources;

class NetflixCodeResource
{
    public function __construct(
        public ?string $code,
        public string $real_email,
        public ?string $message,
        public ?string $link,
        public ?string $read_at,
        public ?string $created_at
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            code: $data['code'] ?? null,
            real_email: (string) ($data['real_email'] ?? ''),
            message: $data['message'] ?? null,
            link: $data['link'] ?? null,
            read_at: $data['read_at'] ?? null,
            created_at: $data['created_at'] ?? null
        );
    }
}