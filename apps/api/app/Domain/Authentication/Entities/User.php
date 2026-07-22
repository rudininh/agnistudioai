<?php

namespace App\Domain\Authentication\Entities;

use DateTimeImmutable;
use DateTimeInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use App\Domain\Authentication\ValueObjects\Email;
use App\Domain\Authentication\ValueObjects\Password;

class User
{
    private UuidInterface \;
    private string \;
    private string \;
    private Email \;
    private Password \;
    private bool \;
    private bool \;
    private ?DateTimeInterface \;
    private ?DateTimeInterface \;
    private DateTimeImmutable \;
    private DateTimeImmutable \;

    public function __construct(
        string \,
        string \,
        string \,
        string \
    ) {
        \->id = Uuid::uuid4();
        \->firstName = trim(\);
        \->lastName = trim(\);
        \->email = new Email(\);
        \->password = new Password(\);
        \->isActive = true;
        \->isVerified = false;
        \->emailVerifiedAt = null;
        \->lastLoginAt = null;
        \->createdAt = new DateTimeImmutable();
        \->updatedAt = new DateTimeImmutable();
    }

    // Getters
    public function getId(): UuidInterface
    {
        return \->id;
    }

    public function getFirstName(): string
    {
        return \->firstName;
    }

    public function getLastName(): string
    {
        return \->lastName;
    }

    public function getFullName(): string
    {
        return trim(\->firstName . ' ' . \->lastName);
    }

    public function getEmail(): Email
    {
        return \->email;
    }

    public function getEmailValue(): string
    {
        return \->email->getValue();
    }

    public function isActive(): bool
    {
        return \->isActive;
    }

    public function isVerified(): bool
    {
        return \->isVerified;
    }

    public function getEmailVerifiedAt(): ?DateTimeInterface
    {
        return \->emailVerifiedAt;
    }

    public function getLastLoginAt(): ?DateTimeInterface
    {
        return \->lastLoginAt;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return \->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return \->updatedAt;
    }

    // Setters
    public function setFirstName(string \): void
    {
        \->firstName = trim(\);
        \->updatedAt = new DateTimeImmutable();
    }

    public function setLastName(string \): void
    {
        \->lastName = trim(\);
        \->updatedAt = new DateTimeImmutable();
    }

    public function setEmail(string \): void
    {
        \->email = new Email(\);
        \->updatedAt = new DateTimeImmutable();
    }

    public function setPassword(string \): void
    {
        \->password = new Password(\);
        \->updatedAt = new DateTimeImmutable();
    }

    public function activate(): void
    {
        \->isActive = true;
        \->updatedAt = new DateTimeImmutable();
    }

    public function deactivate(): void
    {
        \->isActive = false;
        \->updatedAt = new DateTimeImmutable();
    }

    public function verifyEmail(): void
    {
        \->isVerified = true;
        \->emailVerifiedAt = new DateTimeImmutable();
        \->updatedAt = new DateTimeImmutable();
    }

    public function updateLastLogin(): void
    {
        \->lastLoginAt = new DateTimeImmutable();
        \->updatedAt = new DateTimeImmutable();
    }

    public function updateTimestamp(): void
    {
        \->updatedAt = new DateTimeImmutable();
    }

    // Domain Methods
    public function changeName(string \, string \): void
    {
        \->setFirstName(\);
        \->setLastName(\);
    }

    public function changeEmail(string \): void
    {
        \->setEmail(\);
        // When email changes, verification status should be reset
        \->isVerified = false;
        \->emailVerifiedAt = null;
        \->updatedAt = new DateTimeImmutable();
    }

    public function changePassword(string \): void
    {
        \->setPassword(\);
        \->updatedAt = new DateTimeImmutable();
    }

    // Authentication Methods
    public function authenticate(string \): bool
    {
        if (!\->isActive()) {
            return false;
        }

        \ = \->password->verify(\);

        if (\) {
            \->updateLastLogin();
        }

        return \;
    }

    public function needsPasswordReset(): bool
    {
        return \->password->needsRehash();
    }

    // Equality
    public function equals(self \): bool
    {
        return \->id->equals(\->getId());
    }

    // String representation
    public function __toString(): string
    {
        return \->getFullName() . ' <' . \->getEmailValue() . '>';
    }
}
