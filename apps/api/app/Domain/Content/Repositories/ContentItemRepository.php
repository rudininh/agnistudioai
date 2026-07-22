
<?php

namespace App\Domain\Content\Repositories;

use App\Domain\Content\Entities\ContentItem;

interface ContentItemRepository
{
    public function findById(string \): ?ContentItem;
    
    public function findByIdeaId(string \): ?ContentItem;
    
    public function findByIds(array \): array;
    
    public function findByStatus(array \): array;
    
    public function findByDateRange(string \, \DateTimeInterface \, \DateTimeInterface \): array;
    
    public function save(ContentItem \): void;
    
    public function delete(ContentItem \): void;
    
    public function deleteByIdeaId(string \): void;
    
    public function countByStatus(string \): int;
    
    public function countByWorkspaceAndDateRange(string \, \DateTimeInterface \, \DateTimeInterface \): int;
}