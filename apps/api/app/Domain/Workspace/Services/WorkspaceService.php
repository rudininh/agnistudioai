
<?php

namespace App\Domain\Workspace\Services;

use App\Domain\Workspace\Entities\Workspace;
use App\Domain\Workspace\Entities\WorkspaceMember;
use App\Domain\Authentication\Entities\User;

interface WorkspaceService
{
    public function createWorkspace(string \, string \, string \, ?string \ = null): Workspace;
    
    public function getWorkspace(string \): ?Workspace;
    
    public function getWorkspaceBySlug(string \): ?Workspace;
    
    public function updateWorkspace(string \, string \, ?string \ = null): Workspace;
    
    public function deleteWorkspace(string \): void;
    
    public function inviteUser(string \, string \, string \): WorkspaceMember;
    
    public function removeUser(string \, string \): void;
    
    public function changeUserRole(string \, string \, string \): WorkspaceMember;
    
    public function getWorkspaceMembers(string \): array;
    
    public function getUserWorkspaces(string \): array;
    
    public function userCanAccessWorkspace(string \, string \): bool;
    
    public function userCanManageWorkspace(string \, string \): bool;
}