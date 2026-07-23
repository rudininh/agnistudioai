<?php

namespace App\Domain\Workspace\Entities;

use App\Domain\Authentication\Entities\User;
use DateTimeImmutable;
use DateTimeInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class Workspace implements \JsonSerializable
{
    private UuidInterface $id;

    private string $name;

    private string $slug;

    private ?string $description;

    private UuidInterface $ownerId;

    private bool $isActive;

    private DateTimeImmutable $createdAt;

    private ?DateTimeImmutable $updatedAt;

    private array $settings = [];

    public function __construct(
        string $name,
        string $description,
        UuidInterface $ownerId,
        ?string $id = null,
        ?UuidInterface $slug = null,
        ?DateTimeImmutable $createdAt = null
    ) {
        $this->id = $id ?? Uuid::uuid4();
        $this->name = trim($name);
        $this->slug = $this->generateSlug($name);
        $this->description = $description ? trim($description) : null;
        $this->ownerId = $ownerId;
        $this->isActive = true;
        $this->createdAt = $createdAt ?? new DateTimeImmutable;
        $this->updatedAt = clone $this->createdAt;
        $this->settings = [
            'timezone' => 'UTC',
            'dateFormat' => 'Y-m-d',
            'timeFormat' => 'H:i',
            'notificationPreference' => 'email',
            'defaultView' => 'dashboard',
        ];
    }

    private function generateSlug(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
        $slug = preg_replace('/-+/', '-', $slug);
        $slug = trim($slug, '-');
        if ($slug === '') {
            throw new \InvalidArgumentException('Slug cannot be empty after processing');
        }

        return $slug;
    }

    // Getters
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getOwnerId(): UuidInterface
    {
        return $this->ownerId;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function getSettings(): array
    {
        return $this->settings;
    }

    public function getSetting(string $key, mixed $default = null): mixed
    {
        return $this->settings[$key] ?? $default;
    }

    // Setters
    public function setName(string $name): void
    {
        $this->name = trim($name);
        $this->updatedAt = new DateTimeImmutable;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description ? trim($description) : null;
        $this->updatedAt = new DateTimeImmutable;
    }

    public function setSetting(string $key, mixed $value): void
    {
        $this->settings[$key] = $value;
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

    // Business Methods
    public function canBeManagedBy(User $user): bool
    {
        // Owner can always manage
        if ($this->getId()->equals($user->getId())) {
            return true;
        }

        // TODO: Check if user has appropriate permissions through workspace membership
        // This would be implemented when we have the WorkspaceMember entity
        return false;
    }

    public function canBeAccessedBy(User $user): bool
    {
        if ($this->canBeManagedBy($user)) {
            return true;
        }

        // TODO: Check if user is a member of the workspace
        return false;
    }

    // JSON Serialization
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id->toString(),
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'ownerId' => $this->ownerId->toString(),
            'isActive' => $this->isActive,
            'createdAt' => $this->createdAt->toISO8601(),
            'updatedAt' => $this->updatedAt?->toISO8601(),
            'settings' => $this->settings,
        ];
    }

    // Equality
    public function equals(self $other): bool
    {
        return $this->id->equals($other->getId());
    }

    // String representation
    public function __toString(): string
    {
        return $this->name;
    }
}
