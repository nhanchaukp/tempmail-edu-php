<?php

namespace Nhanchaukp\TempmailEdu;

final class Config
{
    public function __construct(
        public string $baseUri = 'https://tempmail.id.vn/api',
        public float $timeout = 10.0,
        public array $defaultHeaders = []
    ) {
    }
}