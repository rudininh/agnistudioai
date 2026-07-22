
<?php

namespace App\Domain\Workspace\Repositories;

use App\Domain\Workspace\Entities\Workspace;
use App\Domain\Authentication\Entities\User;

interface WorkspaceRepository
{
    public function findById(string \): ?Workspace;
    
    public function findByOwnerId(string \): array;
    
    public function findByIds(array \): array;
    
    public function save(Workspace \): void;
    
    public function delete(Workspace \): void;
    
    public function existsBySlug(string \, ?string \ = null): bool;
    
    public function countByOwner(string \): int;
    
    public function findAll(int \ = 20, int \ = 0): array;
}