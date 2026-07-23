<?php

namespace App\Infrastructure\Content\Repositories;

use App\Domain\Content\Entities\ContentIdea;
use App\Domain\Content\Repositories\ContentIdeaRepository;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;

class EloquentContentIdeaRepository implements ContentIdeaRepository
{
    /**
     * Find a content idea by its ID.
     */
    public function findById(string $id): ?ContentIdea
    {
        $row = DB::table('content_ideas')->where('id', $id)->first();
        if (! $row) {
            return null;
        }

        return $this->modelToEntity($row);
    }

    /**
     * Find all content ideas by project ID.
     */
    public function findByProjectId(string $projectId): array
    {
        $rows = DB::table('content_ideas')
            ->where('project_id', $projectId)
            ->get();

        return array_map([$this, 'modelToEntity'], $rows->toArray());
    }

    /**
     * Find content ideas by their IDs.
     */
    public function findByIds(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }
        $rows = DB::table('content_ideas')
            ->whereIn('id', $ids)
            ->get();

        return array_map([$this, 'modelToEntity'], $rows->toArray());
    }

    /**
     * Find content ideas by status and project ID.
     */
    public function findByStatus(string $status, string $projectId): array
    {
        $rows = DB::table('content_ideas')
            ->where('status', $status)
            ->where('project_id', $projectId)
            ->get();

        return array_map([$this, 'modelToEntity'], $rows->toArray());
    }

    /**
     * Find content ideas by status across all projects.
     */
    public function findByStatusAcrossProjects(string $status): array
    {
        $rows = DB::table('content_ideas')
            ->where('status', $status)
            ->get();

        return array_map([$this, 'modelToEntity'], $rows->toArray());
    }

    /**
     * Save a content idea.
     */
    public function save(ContentIdea $contentIdea): void
    {
        $data = [
            'id' => $contentIdea->getId()->toString(),
            'project_id' => $contentIdea->getProjectId()->toString(),
            'title' => $contentIdea->getTitle(),
            'description' => $contentIdea->getDescription(),
            'content_type' => $contentIdea->getContentType(),
            'status' => $contentIdea->getStatus(),
            'scheduled_for' => $contentIdea->getScheduledFor()?->format('Y-m-d H:i:s'),
            'updated_at' => now(),
        ];
        // Check if content idea exists
        $existing = DB::table('content_ideas')
            ->where('id', $contentIdea->getId()->toString())
            ->first();
        if ($existing) {
            // Update existing
            unset($data['created_at']);
            DB::table('content_ideas')
                ->where('id', $contentIdea->getId()->toString())
                ->update($data);
        } else {
            // Insert new
            $data['created_at'] = now();
            DB::table('content_ideas')->insert($data);
        }
    }

    /**
     * Delete a content idea.
     */
    public function delete(ContentIdea $contentIdea): void
    {
        DB::table('content_ideas')
            ->where('id', $contentIdea->getId()->toString())
            ->delete();
    }

    /**
     * Delete content ideas by project ID.
     */
    public function deleteByProjectId(string $projectId): void
    {
        DB::table('content_ideas')
            ->where('project_id', $projectId)
            ->delete();
    }

    /**
     * Count content ideas by project.
     */
    public function countByProject(string $projectId): int
    {
        return (int) DB::table('content_ideas')
            ->where('project_id', $projectId)
            ->count();
    }

    /**
     * Count content ideas by project and status.
     */
    public function countByProjectAndStatus(string $projectId, string $status): int
    {
        return (int) DB::table('content_ideas')
            ->where('project_id', $projectId)
            ->where('status', $status)
            ->count();
    }

    /**
     * Convert a database model to a domain entity.
     */
    private function modelToEntity(array $row): ContentIdea
    {
        $contentIdea = new ContentIdea(
            Uuid::fromString($row['project_id']),
            $row['title'],
            $row['description'],
            $row['content_type']
        );
        $contentIdea->setId(Uuid::fromString($row['id']));
        $contentIdea->setStatus($row['status'] ?? 'idea');
        if (! empty($row['scheduled_for'])) {
            $contentIdea->setScheduledFor(
                \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $row['scheduled_for'])
            );
        }

        // Note: We don't have setters for createdAt/updatedAt in the entity
        // For now, we'll note that the entity's timestamps might not match the DB
        return $contentIdea;
    }
}
