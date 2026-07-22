
<?php

namespace App\Domain\Content\Services;

use App\Domain\Content\Entities\Project;
use App\Domain\Content\Entities\ContentIdea;
use App\Domain\Content\Entities\ContentItem;
use App\Domain\Workspace\Entities\Workspace;
use App\Domain\Authentication\Entities\User;

interface ContentService
{
    // Project Management
    public function createProject(Workspace \, string \, string \, ?string \ = null): Project;
    
    public function getProject(string \): ?Project;
    
    public function updateProject(string \, string \, ?string \ = null): Project;
    
    public function deleteProject(string \): void;
    
    public function archiveProject(string \): void;
    
    public function getProjectsByWorkspace(string \): array;
    
    // Content Idea Management
    public function createIdea(Project \, string \, string \, string \): ContentIdea;
    
    public function getIdea(string \): ?ContentIdea;
    
    public function updateIdea(string \, string \, string \, string \): ContentIdea;
    
    public function deleteIdea(string \): void;
    
    public function updateIdeaStatus(string \, string \): ContentIdea;
    
    public function updateIdeaPriority(string \, int \): ContentIdea;
    
    public function addTagToIdea(string \, string \): void;
    
    public function removeTagFromIdea(string \, string \): void;
    
    public function getIdeasByProject(string \): array;
    
    public function getIdeasByProjectAndStatus(string \, string \): array;
    
    // Content Item Management
    public function createContent(ContentIdea \, string \, string \, string \): ContentItem;
    
    public function getContent(string \): ?ContentItem;
    
    public function updateContent(string \, string \, string \, string \): ContentItem;
    
    public function deleteContent(string \): void;
    
    public function publishContent(string \): ContentItem;
    
    public function scheduleContent(string \, \DateTimeInterface \): ContentItem;
    
    public function getContentByIdea(string \): ?ContentItem;
    
    public function getPublishedContentByWorkspace(string \, int \ = 10, int \ = 0): array;
    
    public function getContentFeed(string \, string \ = 'published', int \ = 20, int \ = 0): array;
}