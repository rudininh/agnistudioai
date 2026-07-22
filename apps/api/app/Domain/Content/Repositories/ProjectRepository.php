
<?php

namespace App\Domain\Content\Repositories;

use App\Domain\Content\Entities\Project;

interface ProjectRepository
{
    public function findById(string \): ?Project;
    
    public function findByWorkspaceId(string \): array;
    
    public function findByIds(array \): array;
    
    public function findBySlug(string \, string \): ?Project;
    
    public function save(Project \): void;
    
    public function delete(Project \): void;
    
    public function deleteByWorkspaceId(string \): void;
    
    public function countByWorkspace(string \): int;
    
    public function existsBySlug(string \, string \, ?string \ = null): bool;
}