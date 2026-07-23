<?php

namespace App\Infrastructure\Persistence\Eloquent\Authentication;

use App\Domain\Authentication\Entities\User;
use App\Domain\Authentication\Repositories\UserRepository;
use App\Domain\Authentication\ValueObjects\Email;
use App\Domain\Authentication\ValueObjects\Password;
use Illuminate\Support\Facades\DB;

class EloquentUserRepository implements UserRepository
{
    /**
     * Find a user by their ID.
     */
    public function findById(string $id): ?User
    {
        $model = DB::table('users')->where('id', $id)->first();

        if (! $model) {
            return null;
        }

        return $this->modelToEntity((array) $model);
    }

    /**
     * Find a user by their email.
     */
    public function findByEmail(Email $email): ?User
    {
        $model = DB::table('users')->where('email', $email->getValue())->first();

        if (! $model) {
            return null;
        }

        return $this->modelToEntity((array) $model);
    }

    /**
     * Find users by their IDs.
     */
    public function findByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        $models = DB::table('users')->whereIn('id', $ids)->get();

        return array_map([$this, 'modelToEntity'], $models->toArray());
    }

    /**
     * Save a user.
     */
    public function save(User $user): void
    {
        $data = [
            'id' => $user->getId()->toString(),
            'first_name' => $user->getFirstName(),
            'last_name' => $user->getLastName(),
            'email' => $user->getEmailValue(),
            'password' => $user->getPassword()->getHash(), // Assuming Password has getHash() method
            'is_active' => $user->isActive(),
            'is_verified' => $user->isVerified(),
            'email_verified_at' => $user->getEmailVerifiedAt()?->format('Y-m-d H:i:s'),
            'last_login_at' => $user->getLastLoginAt()?->format('Y-m-d H:i:s'),
            'updated_at' => now(),
        ];

        // Check if user exists
        $existing = DB::table('users')->where('id', $user->getId()->toString())->first();

        if ($existing) {
            // Update existing
            DB::table('users')->where('id', $user->getId()->toString())->update($data);
        } else {
            // Insert new
            $data['created_at'] = now();
            $data['updated_at'] = now();
            DB::table('users')->insert($data);
        }
    }

    /**
     * Delete a user.
     */
    public function delete(User $user): void
    {
        DB::table('users')->where('id', $user->getId()->toString())->delete();
    }

    /**
     * Count users.
     */
    public function count(): int
    {
        return DB::table('users')->count();
    }

    /**
     * Find all users with limit and offset.
     */
    public function findAll(int $limit = 20, int $offset = 0): array
    {
        $models = DB::table('users')
            ->offset($offset)
            ->limit($limit)
            ->get();

        return array_map([$this, 'modelToEntity'], $models->toArray());
    }

    /**
     * Check if a user exists by email.
     */
    public function existsByEmail(Email $email): bool
    {
        return DB::table('users')->where('email', $email->getValue())->exists();
    }

    /**
     * Convert a database model to a domain entity.
     */
    private function modelToEntity(array $model): User
    {
        // Use the factory method to create user from persistence data
        return User::createFromPersistence($model);
    }
}
