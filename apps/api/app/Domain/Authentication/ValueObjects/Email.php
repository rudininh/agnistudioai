<?php

namespace App\Domain\Authentication\ValueObjects;

use InvalidArgumentException;

final class Email
{
    private string \;

    public function __construct(string \)
    {
        \->email = \->validateAndNormalize(\);
    }

    private function validateAndNormalize(string \): string
    {
        \ = trim(\);

        if (\ === '') {
            throw new InvalidArgumentException('Email cannot be empty');
        }

        if (!filter_var(\, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Invalid email format');
        }

        // Additional length check
        if (strlen(\) > 255) {
            throw new InvalidArgumentException('Email is too long');
        }

        return strtolower(\);
    }

    public function getValue(): string
    {
        return \->email;
    }

    public function __toString(): string
    {
        return \->email;
    }

    public function equals(Email \): bool
    {
        return \->email === \->email;
    }
}
