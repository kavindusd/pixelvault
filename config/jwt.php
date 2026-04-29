<?php

declare(strict_types=1);

return [
    'secret' => $_ENV['JWT_SECRET'] ?? getenv('JWT_SECRET') ?: 'change-this-secret-in-production',
    'issuer' => $_ENV['JWT_ISSUER'] ?? getenv('JWT_ISSUER') ?: 'pixelvault.local',
    'audience' => $_ENV['JWT_AUDIENCE'] ?? getenv('JWT_AUDIENCE') ?: 'pixelvault.web',
    'ttl_seconds' => (int) ($_ENV['JWT_TTL_SECONDS'] ?? getenv('JWT_TTL_SECONDS') ?: 86400),
    'cookie_name' => 'pixelvault_token',
];
