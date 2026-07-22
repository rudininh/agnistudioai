
<?php

namespace App\Domain\Content\Repositories;

use App\Domain\Content\Entities\ContentIdea;

interface ContentIdeaRepository
{
    public function findById(string \): ?ContentIdea;
    
    public function findByProjectId(string \): array;
    
    public function findByIds(array \): array;
    
    public function findByStatus(string \, string \): array;
    
    public function save(ContentIdea \): void;
    
    public function delete(ContentIdea \): void;
    
    public function deleteByProjectId(string \): void;
    
    public function countByProject(string \): int;
    
    public function countByProjectAndStatus(string \, string \): int;
}