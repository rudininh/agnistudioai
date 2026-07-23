<?php

namespace App\Domain\Authentication\Repositories;

use App\Domain\Authentication\Entities\User;
use App\Domain\Authentication\ValueObjects\Email;

interface UserRepository
{
    public function findById(string $id): ?User;

    public function findByEmail(Email $email): ?User;

    public function findByIds(array $ids): array;

    public function save(User $user): void;

    public function delete(User $user): void;

    public function existsByEmail(Email $email): bool;

    public function count(): int;

    public function findAll(int $limit = 20, int $offset = 0): array;
}
