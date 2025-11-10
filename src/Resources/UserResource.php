<?php

namespace Nhanchaukp\TempmailEdu\Resources;

class UserResource
{
    public function __construct(
        public int $id,
        public string $name,
        public string $email,
        public ?string $email_verified_at,
        public ?string $two_factor_confirmed_at,
        public ?int $current_team_id,
        public ?string $profile_photo_path,
        public ?string $created_at,
        public ?string $updated_at,
        public ?string $telegram_id,
        public string $language
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['id'] ?? 0),
            name: (string) ($data['name'] ?? ''),
            email: (string) ($data['email'] ?? ''),
            email_verified_at: $data['email_verified_at'] ?? null,
            two_factor_confirmed_at: $data['two_factor_confirmed_at'] ?? null,
            current_team_id: isset($data['current_team_id']) ? (int)$data['current_team_id'] : null,
            profile_photo_path: $data['profile_photo_path'] ?? null,
            created_at: $data['created_at'] ?? null,
            updated_at: $data['updated_at'] ?? null,
            telegram_id: $data['telegram_id'] ?? null,
            language: (string) ($data['language'] ?? '')
        );
    }
}