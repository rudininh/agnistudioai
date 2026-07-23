<?php

namespace App\Domain\Authentication\Entities;

use App\Domain\Authentication\ValueObjects\Email;
use App\Domain\Authentication\ValueObjects\Password;
use DateTimeImmutable;
use DateTimeInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class User
{
    private UuidInterface $id;

    private string $firstName;

    private string $lastName;

    private Email $email;

    private Password $password;

    private bool $isActive;

    private bool $isVerified;

    private ?DateTimeInterface $emailVerifiedAt;

    private ?DateTimeInterface $lastLoginAt;

    private DateTimeImmutable $createdAt;

    private DateTimeImmutable $updatedAt;

    public function __construct(
        string $firstName,
        string $lastName,
        string $email,
        string $password
    ) {
        $this->id = Uuid::uuid4();
        $this->firstName = trim($firstName);
        $this->lastName = trim($lastName);
        $this->email = new Email($email);
        $this->password = Password::fromPlainText($password);
        $this->isActive = true;
        $this->isVerified = false;
        $this->emailVerifiedAt = null;
        $this->lastLoginAt = null;
        $this->createdAt = new DateTimeImmutable;
        $this->updatedAt = new DateTimeImmutable;
    }

    // Getters
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }

    public function getFullName(): string
    {
        return trim($this->firstName.' '.$this->lastName);
    }

    public function getEmail(): Email
    {
        return $this->email;
    }

    public function getPassword(): Password
    {
        return $this->password;
    }

    public function getEmailValue(): string
    {
        return $this->email->getValue();
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function getEmailVerifiedAt(): ?DateTimeInterface
    {
        return $this->emailVerifiedAt;
    }

    public function getLastLoginAt(): ?DateTimeInterface
    {
        return $this->lastLoginAt;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): DateTimeImmutable
    {
        return $this->updatedAt;
    }

    // Setters
    public function setFirstName(string $firstName): void
    {
        $this->firstName = trim($firstName);
        $this->updatedAt = new DateTimeImmutable;
    }

    public function setLastName(string $lastName): void
    {
        $this->lastName = trim($lastName);
        $this->updatedAt = new DateTimeImmutable;
    }

    public function setEmail(string $email): void
    {
        $this->email = new Email($email);
        $this->updatedAt = new DateTimeImmutable;
    }

    public function setPassword(string $password): void
    {
        $this->password = Password::fromPlainText($password);
        $this->updatedAt = new DateTimeImmutable;
    }

    public function activate(): void
    {
        $this->isActive = true;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function verifyEmail(): void
    {
        $this->isVerified = true;
        $this->emailVerifiedAt = new DateTimeImmutable;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function setIsVerified(bool $isVerified): void
    {
        $this->isVerified = $isVerified;
        $this->updatedAt = new DateTimeImmutable;

        // If setting to false, clear the verification timestamp
        if (! $isVerified) {
            $this->emailVerifiedAt = null;
        }

        // If setting to true and no timestamp exists, set it now
        if ($isVerified && ! $this->emailVerifiedAt) {
            $this->emailVerifiedAt = new DateTimeImmutable;
        }
    }

    public function setEmailVerifiedAt(?DateTimeInterface $dateTime): void
    {
        $this->emailVerifiedAt = $dateTime;
        $this->updatedAt = new DateTimeImmutable;

        // If we set a timestamp, ensure isVerified is true
        if ($dateTime) {
            $this->isVerified = true;
        }
    }

    public function setIsActive(bool $isActive): void
    {
        $this->isActive = $isActive;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function setLastLoginAt(?DateTimeInterface $dateTime): void
    {
        $this->lastLoginAt = $dateTime;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function updateLastLogin(): void
    {
        $this->lastLoginAt = new DateTimeImmutable;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function updateTimestamp(): void
    {
        $this->updatedAt = new DateTimeImmutable;
    }

    // Factory method for hydration
    public static function createFromPersistence(array $data): self
    {
        // Create a temporary user with dummy data (will be overridden)
        $user = new self(
            $data['first_name'],
            $data['last_name'],
            $data['email'],
            'temporary_password' // Will be replaced
        );
        // Override all properties with actual values from the database
        $user->id = Uuid::fromString($data['id']);
        $user->password = Password::fromHash($data['password']);
        $user->isActive = (bool) ($data['is_active'] ?? false);
        $user->isVerified = (bool) ($data['is_verified'] ?? false);
        $user->emailVerifiedAt = ! empty($data['email_verified_at'])
            ? DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['email_verified_at'])
            : null;
        $user->lastLoginAt = ! empty($data['last_login_at'])
            ? DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['last_login_at'])
            : null;
        $user->createdAt = ! empty($data['created_at'])
            ? DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['created_at'])
            : new DateTimeImmutable;
        $user->updatedAt = ! empty($data['updated_at'])
            ? DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $data['updated_at'])
            : new DateTimeImmutable;

        return $user;
    }

    // Domain Methods
    public function changeName(string $firstName, string $lastName): void
    {
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
    }

    public function changeEmail(string $email): void
    {
        $this->setEmail($email);
        // When email changes, verification status should be reset
        $this->isVerified = false;
        $this->emailVerifiedAt = null;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function changePassword(string $password): void
    {
        $this->setPassword($password);
        $this->updatedAt = new DateTimeImmutable;
    }

    // Authentication Methods
    public function authenticate(string $password): bool
    {
        if (! $this->isActive()) {
            return false;
        }
        $result = $this->password->verify($password);
        if ($result) {
            $this->updateLastLogin();
        }

        return $result;
    }

    public function needsPasswordReset(): bool
    {
        return $this->password->needsRehash();
    }

    // Equality
    public function equals(self $other): bool
    {
        return $this->id->equals($other->getId());
    }

    // String representation
    public function __toString(): string
    {
        return $this->getFullName().' <'.$this->getEmailValue().'>';
    }
}
