
<?php

namespace App\Domain\Authentication\Repositories;

use App\Domain\Authentication\Entities\User;
use App\Domain\Authentication\ValueObjects\Email;

interface UserRepository
{
    public function findById(string \): ?User;
    
    public function findByEmail(Email \): ?User;
    
    public function findByIds(array \): array;
    
    public function save(User \): void;
    
    public function delete(User \): void;
    
    public function existsByEmail(Email \): bool;
    
    public function count(): int;
    
    public function findAll(int \ = 20, int \ = 0): array;
}