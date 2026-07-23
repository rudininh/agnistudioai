<?php

namespace App\Domain\Authentication\ValueObjects;

use InvalidArgumentException;

final class Email
{
    private string $email;

    public function __construct(string $email)
    {
        $this->email = $this->validateAndNormalize($email);
    }

    private function validateAndNormalize(string $email): string
    {
        $email = trim($email);
        if ($email === '') {
            throw new InvalidArgumentException('Email cannot be empty');
        }
        if (! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }
        // Additional length check
        if (strlen($email) > 255) {
            throw new InvalidArgumentException('Email is too long');
        }

        return strtolower($email);
    }

    public function getValue(): string
    {
        return $this->email;
    }

    public function __toString(): string
    {
        return $this->email;
    }

    public function equals(Email $email): bool
    {
        return $this->email === $email->email;
    }
}
