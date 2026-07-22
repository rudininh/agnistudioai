
<?php

namespace App\Domain\Workspace\Entities;

use DateTimeImmutable;
use DateTimeInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use App\Domain\Authentication\Entities\User;

class Workspace implements \JsonSerializable
{
    private UuidInterface \;
    private string \;
    private string \;
    private ?string \;
    private UuidInterface \;
    private bool \;
    private DateTimeImmutable \;
    private ?DateTimeImmutable \;
    private array \ = [];

    public function __construct(
        string \,
        string \,
        UuidInterface \,
        ?string \ = null,
        ?UuidInterface \ = null,
        ?DateTimeImmutable \ = null
    ) {
        \->id = \ ?? Uuid::uuid4();
        \->name = trim(\);
        \->slug = \->generateSlug(\);
        \->description = \ ? trim(\) : null;
        \->ownerId = \;
        \->isActive = true;
        \->createdAt = \ ?? new DateTimeImmutable();
        \->updatedAt = clone \->createdAt;
        \->settings = [
            'timezone' => 'UTC',
            'dateFormat' => 'Y-m-d',
            'timeFormat' => 'H:i',
            'notificationPreference' => 'email',
            'defaultView' => 'dashboard'
        ];
    }

    private function generateSlug(string \): string
    {
        \ = strtolower(trim(\));
        \ = preg_replace('/[^a-z0-9-]/', '-', \);
        \ = preg_replace('/-+/', '-', \);
        \ = trim(\, '-');

        if (\ === '') {
            throw new \\InvalidArgumentException('Slug cannot be empty after processing');
        }

        return \;
    }

    // Getters
    public function getId(): UuidInterface
    {
        return \->id;
    }

    public function getName(): string
    {
        return \->name;
    }

    public function getSlug(): string
    {
        return \->slug;
    }

    public function getDescription(): ?string
    {
        return \->description;
    }

    public function getOwnerId(): UuidInterface
    {
        return \->ownerId;
    }

    public function isActive(): bool
    {
        return \->isActive;
    }

    public function getCreatedAt(): DateTimeInterface
    {
        return \->createdAt;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return \->updatedAt;
    }

    public function getSettings(): array
    {
        return \->settings;
    }

    public function getSetting(string \, mixed \ = null): mixed
    {
        return \->settings[\] ?? \;
    }

    // Setters
    public function setName(string \): void
    {
        \->name = trim(\);
        \->updatedAt = new DateTimeImmutable();
    }

    public function setDescription(?string \): void
    {
        \->description = \ ? trim(\) : null;
        \->updatedAt = new DateTimeImmutable();
    }

    public function setSetting(string \, mixed \): void
    {
        \->settings[\] = \;
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

    // Business Methods
    public function canBeManagedBy(User \): bool
    {
        // Owner can always manage
        if (\->getId()->equals(\->ownerId)) {
            return true;
        }

        // TODO: Check if user has appropriate permissions through workspace membership
        // This would be implemented when we have the WorkspaceMember entity
        return false;
    }

    public function canBeAccessedBy(User \): bool
    {
        if (->canBeManagedBy(\)) {
            return true;
        }

        // TODO: Check if user is a member of the workspace
        return false;
    }

    // JSON Serialization
    public function jsonSerialize(): array
    {
        return [
            'id' => \->id->toString(),
            'name' => \->name,
            'slug' => \->slug,
            'description' => \->description,
            'ownerId' => \->ownerId->toString(),
            'isActive' => \->isActive,
            'createdAt' => \->createdAt->toISO8601(),
            'updatedAt' => \->updatedAt?->toISO8601(),
            'settings' => \->settings
        ];
    }

    // Equality
    public function equals(self \): bool
    {
        return \->id->equals(\->getId());
    }

    // String representation
    public function __toString(): string
    {
        return \->name;
    }
}