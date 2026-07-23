<?php

namespace App\Domain\Authentication\ValueObjects;

use InvalidArgumentException;
use RuntimeException;

final class Password
{
    private string $hash;

    private function __construct(string $hash)
    {
        $this->hash = $hash;
    }

    public static function fromPlainText(string $password): self
    {
        // Validate password strength
        if (strlen($password) < 8) {
            throw new InvalidArgumentException('Password must be at least 8 characters long');
        }
        // Hash the password using bcrypt
        $hash = password_hash($password, PASSWORD_BCRYPT, [
            'cost' => 12,
        ]);
        if ($hash === false) {
            throw new RuntimeException('Password hashing failed');
        }

        return new self($hash);
    }

    public static function fromHash(string $hash): self
    {
        // Optionally, validate that the hash is a valid bcrypt hash
        // For now, we assume it's valid
        return new self($hash);
    }

    public function getHash(): string
    {
        return $this->hash;
    }

    public function verify(string $password): bool
    {
        return password_verify($password, $this->hash);
    }

    public function needsRehash(): bool
    {
        return password_needs_rehash($this->hash, PASSWORD_BCRYPT, ['cost' => 12]);
    }
}
