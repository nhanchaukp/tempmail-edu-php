<?php

namespace Nhanchaukp\TempmailEdu\Resources;

class MessageResource
{
    public function __construct(
        public int $id,
        public string $sender_name,
        public string $from,
        public string $to,
        public string $subject,
        public ?string $body,
        public ?string $otp_code,
        public ?string $read_at,
        public ?string $created_at
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['id'] ?? 0),
            sender_name: (string) ($data['sender_name'] ?? ''),
            from: (string) ($data['from'] ?? ''),
            to: (string) ($data['to'] ?? ''),
            subject: (string) ($data['subject'] ?? ''),
            body: $data['body'] ?? null,
            otp_code: $data['otp_code'] ?? null,
            read_at: $data['read_at'] ?? null,
            created_at: $data['created_at'] ?? null
        );
    }
}