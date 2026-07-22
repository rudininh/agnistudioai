
<?php

namespace App\Domain\Workspace\Repositories;

use App\Domain\Workspace\Entities\WorkspaceMember;

interface WorkspaceMemberRepository
{
    public function findById(string \): ?WorkspaceMember;
    
    public function findByUserAndWorkspace(string \, string \): ?WorkspaceMember;
    
    public function findByWorkspaceId(string \): array;
    
    public function findByUserId(string \): array;
    
    public function save(WorkspaceMember \): void;
    
    public function delete(WorkspaceMember \): void;
    
    public function deleteByWorkspaceId(string \): void;
    
    public function deleteByUserId(string \): void;
    
    public function countByWorkspace(string \): int;
    
    public function countByUser(string \): int;
    
    public function exists(string \, string \): bool;
}