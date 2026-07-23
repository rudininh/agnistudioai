<?php

namespace App\Infrastructure\Auth\Repositories;

use App\Domain\Authentication\Entities\User;
use App\Domain\Authentication\Repositories\UserRepository;
use App\Domain\Authentication\ValueObjects\Email;

class EloquentUserRepository implements UserRepository
{
    public function findById(string $id): ?User
    {
        $user = \App\Models\User::find($id);
        if (! $user) {
            return null;
        }

        return new User(
            $user->first_name,
            $user->last_name,
            $user->email,
            $user->password // This is already hashed
        );
    }

    public function findByEmail(Email $email): ?User
    {
        $user = \App\Models\User::where('email', $email->value())->first();
        if (! $user) {
            return null;
        }

        return new User(
            $user->first_name,
            $user->last_name,
            $user->email,
            $user->password // This is already hashed
        );
    }

    public function existsByEmail(Email $email): bool
    {
        return \App\Models\User::where('email', $email->value())->exists();
    }

    public function save(User $user): void
    {
        $userModel = \App\Models\User::updateOrCreate(
            ['email' => $user->email()->value()],
            [
                'first_name' => $user->firstName(),
                'last_name' => $user->lastName(),
                'email' => $user->email()->value(),
                'password' => $user->password()->value(), // Already hashed
            ]
        );
        // If the User entity uses UUIDs, we might need to update the entity
        // For now, we'll assume the entity gets its ID from the database
        // This would need adjustment based on how User entity handles ID
    }
}
