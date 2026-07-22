<?php

namespace App\Domain\Authentication\ValueObjects;

use InvalidArgumentException;
use RuntimeException;

final class Password
{
    private string \;

    public function __construct(string \)
    {
        \->hash = \->hashPassword(\);
    }

    private function hashPassword(string \): string
    {
        // Validate password strength
        if (strlen(\) < 8) {
            throw new InvalidArgumentException('Password must be at least 8 characters long');
        }

        // Hash the password using bcrypt
        \ = password_hash(\, PASSWORD_BCRYPT, [
            'cost' => 12,
        ]);

        if (\ === false) {
            throw new RuntimeException('Password hashing failed');
        }

        return \;
    }

    public function getHash(): string
    {
        return \->hash;
    }

    public function verify(string \): bool
    {
        return password_verify(\, \->hash);
    }

    public function needsRehash(): bool
    {
        return password_needs_rehash(\->hash, PASSWORD_BCRYPT, ['cost' => 12]);
    }
}
