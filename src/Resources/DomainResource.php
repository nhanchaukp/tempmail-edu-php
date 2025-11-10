<?php

namespace Nhanchaukp\TempmailEdu\Resources;

class DomainResource
{
    public function __construct(
        public int $id,
        public string $name
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: (int) ($data['id'] ?? 0),
            name: (string) ($data['name'] ?? '')
        );
    }
}