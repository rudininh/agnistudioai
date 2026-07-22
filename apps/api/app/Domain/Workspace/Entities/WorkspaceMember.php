<?php

namespace App\Domain\Workspace\Entities;

use DateTimeImmutable;
use DateTimeInterface;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use App\Domain\Authentication\Entities\User;

class WorkspaceMember
{
    private UuidInterface $id;
    private UuidInterface $workspaceId;
    private UuidInterface $userId;
    private string $role;
    private bool $isActive;
    private ?DateTimeImmutable $leftAt;
    private DateTimeImmutable $joinedAt;
    private DateTimeImmutable $createdAt;
    private DateTimeImmutable $updatedAt;

    // Role constants
    public const ROLE_OWNER = 'owner';
    public const ROLE_ADMIN = 'admin';
    public const ROLE_MEMBER = 'member';
    public const ROLE_VIEWER = 'viewer';

    private array $validRoles = [
        self::ROLE_OWNER,
        self::ROLE_ADMIN,
        self::ROLE_MEMBER,
        self::ROLE_VIEWER
    ];

    public function __construct(
        UuidInterface $workspaceId,
        UuidInterface $userId,
        string $role = self::ROLE_MEMBER,
        ?UuidInterface $id = null,
        ?DateTimeImmutable $joinedAt = null
    ) {
        if (!in_array($role, $this->validRoles, true)) {
            throw new \InvalidArgumentException("Invalid role: {$role}");
        }

        $this->id = $id ?? Uuid::uuid4();
        $this->workspaceId = $workspaceId;
        $this->userId = $userId;
        $this->role = $role;
        $this->isActive = true;
        $this->joinedAt = $joinedAt ?? new DateTimeImmutable();
        $this->leftAt = null;
        $this->createdAt = new DateTimeImmutable();
        $this->updatedAt = clone $this->createdAt;
    }

    // Getters
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getWorkspaceId(): UuidInterface
    {
        return $this->workspaceId;
    }

    public function getUserId(): UuidInterface
    {
        return $this->userId;
    }

    public function getRole(): string
    {
        return $this->role;
    }

    public function isActive(): bool
    {
        return $this->isActive && $this->leftAt === null;
    }

    public function getJoinedAt(): DateTimeInterface
    {
        return $this->joinedAt;
    }

    public function getLeftAt(): ?DateTimeInterface
    {
        return $this->leftAt;
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
    public function setRole(string $role): void
    {
        if (!in_array($role, $this->validRoles, true)) {
            throw new \InvalidArgumentException("Invalid role: {$role}");
        }

        $this->role = $role;
        $this->updatedAt = new DateTimeImmutable();
    }

    public function deactivate(): void
    {
        $this->isActive = false;
        $this->leftAt = new DateTimeImmutable();
        $this->updatedAt = new DateTimeImmutable();
    }

    public function reactivate(): void
    {
        $this->isActive = true;
        $this->leftAt = null;
        $this->updatedAt = new DateTimeImmutable();
    }

    // Role Checking Methods
    public function isOwner(): bool
    {
        return $this->role === self::ROLE_OWNER;
    }

    public function isAdmin(): bool
    {
        return $this->role === self::ROLE_ADMIN;
    }

    public function isMember(): bool
    {
        return in_array($this->role, [self::ROLE_OWNER, self::ROLE_ADMIN, self::ROLE_MEMBER], true);
    }

    public function canManageMembers(): bool
    {
        return in_array($this->role, [self::ROLE_OWNER, self::ROLE_ADMIN], true);
    }

    public function canManageWorkspace(): bool
    {
        return in_array($this->role, [self::ROLE_OWNER, self::ROLE_ADMIN], true);
    }

    public function canEditContent(): bool
    {
        return in_array($this->role, [self::ROLE_OWNER, self::ROLE_ADMIN, self::ROLE_MEMBER], true);
    }

    public function canViewOnly(): bool
    {
        return $this->role === self::ROLE_VIEWER;
    }

    // Utility Methods
    public function belongsToUser(UuidInterface $userId): bool
    {
        return $this->userId->equals($userId));
    {
        return $this->userId->equals($userId);
    }

    public function belongsToWorkspace(UoidInterface $workspaceIdfalse);
    {
        return $this->workspaceId->equals($workspaceId);
    }

    // JSON Serialization
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id->toString(),
            'workspaceId' => $this->workspaceId->toString(),
            'userId' => $this->userId->toString(),
            'role' => $this->role,
            'isActive' => $this->isActive(),
            'joinedAt' => $this->joinedAt->toISO8601(),
            'leftAt' => $this->leftAt?->toISO8601(),
            'createdAt' => $this->createdAt->toISO8601(),
            'updatedAt' => $this->updatedAt->toISO8601()
        ];
    }

    // Equality
    public function equals(self $otherworkspaceId**this->id->equals($other->getId()));
    {
        return $this->id->equals($other->getId());
    }
}