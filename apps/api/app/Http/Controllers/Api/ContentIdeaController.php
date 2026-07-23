<?php

namespace App\Http\Controllers\Api;

use App\Domain\Content\Services\ContentIdeaService;
use App\Http\Requests\StoreContentIdeaRequest;
use App\Http\Requests\UpdateContentIdeaRequest;
use App\Http\Resources\ContentIdeaResource;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ContentIdeaController extends ApiBaseController
{
    protected $contentIdeaService;

    public function __construct(
        ContentIdeaService $contentIdeaService
    ) {
        $this->contentIdeaService = $contentIdeaService;
    }

    /**
     * Display a listing of content ideas for a project.
     *
     * @OA\Get(
     *     path="/api/v1/workspaces/projects/{project_id}/ideas",
     *     summary="List content ideas",
     *     description="Returns a list of content ideas for the specified project",
     *     operationId="listContentIdeas",
     *     tags={"Content Ideas"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="project_id",
     *         in="path",
     *         required=true,
     *         description="ID of the project",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="status",
     *         in="query",
     *         required=false,
     *         description="Filter by status",
     *
     *         @OA\Schema(type="string", enum={"idea","in_progress","review","approved","rejected"})
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Content ideas retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Content ideas retrieved successfully"),
     *             @OA\Property(property="data", type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/ContentIdeaResource")
     *             )
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     */
    public function index(Request $request)
    {
        $projectId = $request->query('project_id');
        $status = $request->query('status');
        if ($projectId) {
            $ideas = $this->contentIdeaService->getIdeasByProjectId($projectId);
        } elseif ($status) {
            // Get ideas by status across all projects
            $ideas = $this->contentIdeaService->getIdeasByStatusAcrossProjects($status);
        } else {
            // Get all ideas - we'd need a method for this too
            // For now, we'll return empty as we don't have a method to get all ideas
            // TODO: Add getAllIdeas method to service if needed
            $ideas = [];
        }

        return $this->apiResponse(
            ContentIdeaResource::collection($ideas),
            'Content ideas retrieved successfully'
        );
    }

    /**
     * Store a newly created content idea in storage.
     *
     * @OA\Post(
     *     path="/api/v1/workspaces/projects/{project_id}/ideas",
     *     summary="Create content idea",
     *     description="Creates a new content idea in the specified project",
     *     operationId="createContentIdea",
     *     tags={"Content Ideas"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="project_id",
     *         in="path",
     *         required=true,
     *         description="ID of the project",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"title","description","content_type"},
     *
     *             @OA\Property(property="title", type="string", example="Blog post about Laravel"),
     *             @OA\Property(property="description", type="string", example="A detailed tutorial on Laravel Eloquent"),
     *             @OA\Property(property="content_type", type="string", example="blog"),
     *             @OA\Property(property="scheduled_for", type="string", format="date-time", example="2025-12-31T10:00:00Z")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Content idea created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Content idea created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/ContentIdeaResource")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     */
    public function store(StoreContentIdeaRequest $request)
    {
        try {
            $idea = $this->contentIdeaService->createIdea(
                $request->validated('title'),
                $request->validated('description'),
                $request->validated('content_type'),
                $request->validated('project_id'),
                $request->has('scheduled_for') ? $request->validated('scheduled_for') : null
            );

            return $this->apiResponse(
                new ContentIdeaResource($idea),
                'Content idea created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->apiError(
                'Failed to create content idea',
                500,
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Display the specified content idea.
     *
     * @OA\Get(
     *     path="/api/v1/workspaces/projects/{project_id}/ideas/{idea_id}",
     *     summary="Get content idea",
     *     description="Returns the details of a specific content idea",
     *     operationId="getContentIdea",
     *     tags={"Content Ideas"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="project_id",
     *         in="path",
     *         required=true,
     *         description="ID of the project",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="idea_id",
     *         in="path",
     *         required=true,
     *         description="ID of the content idea",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Content idea retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Content idea retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/ContentIdeaResource")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Content idea not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Content idea not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     */
    public function show(string $id)
    {
        $idea = $this->contentIdeaService->getIdeaById($id);
        if (! $idea) {
            return $this->apiError('Content idea not found', 404);
        }

        return $this->apiResponse(
            new ContentIdeaResource($idea),
            'Content idea retrieved successfully'
        );
    }

    /**
     * Update the specified content idea in storage.
     *
     * @OA\Put(
     *     path="/api/v1/workspaces/projects/{project_id}/ideas/{idea_id}",
     *     summary="Update content idea",
     *     description="Updates an existing content idea",
     *     operationId="updateContentIdea",
     *     tags={"Content Ideas"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="project_id",
     *         in="path",
     *         required=true,
     *         description="ID of the project",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="idea_id",
     *         in="path",
     *         required=true,
     *         description="ID of the content idea",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="title", type="string", example="Updated Blog Post Title"),
     *             @OA\Property(property="description", type="string", example="Updated description"),
     *             @OA\Property(property="content_type", type="string", example="blog"),
     *             @OA\Property(property="scheduled_for", type="string", format="date-time", example="2025-12-31T11:00:00Z")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Content idea updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Content idea updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/ContentIdeaResource")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=400,
     *         description="Bad request",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Validation error"),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Content idea not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Content idea not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     */
    public function update(UpdateContentIdeaRequest $request, string $id)
    {
        try {
            $idea = $this->contentIdeaService->getIdeaById($id);
            if (! $idea) {
                return $this->apiError('Content idea not found', 404);
            }
            $idea = $this->contentIdeaService->updateIdea(
                $idea,
                $request->validated('title'),
                $request->validated('description'),
                $request->validated('content_type'),
                $request->has('scheduled_for') ? $request->validated('scheduled_for') : null
            );

            return $this->apiResponse(
                new ContentIdeaResource($idea),
                'Content idea updated successfully'
            );
        } catch (\Exception $e) {
            return $this->apiError(
                'Failed to update content idea',
                500,
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Remove the specified content idea from storage.
     *
     * @OA\Delete(
     *     path="/api/v1/workspaces/projects/{project_id}/ideas/{idea_id}",
     *     summary="Delete content idea",
     *     description="Deletes a content idea by ID",
     *     operationId="deleteContentIdea",
     *     tags={"Content Ideas"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="project_id",
     *         in="path",
     *         required=true,
     *         description="ID of the project",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="idea_id",
     *         in="path",
     *         required=true,
     *         description="ID of the content idea",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Content idea deleted successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Content idea deleted successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Content idea not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Content idea not found")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Forbidden",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Forbidden")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     */
    public function destroy(string $id)
    {
        try {
            $idea = $this->contentIdeaService->getIdeaById($id);
            if (! $idea) {
                return $this->apiError('Content idea not found', 404);
            }
            $this->contentIdeaService->deleteIdea($idea);

            return $this->apiResponse(null, 'Content idea deleted successfully');
        } catch (\Exception $e) {
            return $this->apiError(
                'Failed to delete content idea',
                500,
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Approve a content idea.
     */
    public function approve(string $id)
    {
        try {
            $idea = $this->contentIdeaService->getIdeaById($id);
            if (! $idea) {
                return $this->apiError('Content idea not found', 404);
            }
            $this->contentIdeaService->approve($idea);

            return $this->apiResponse(
                new ContentIdeaResource($idea),
                'Content idea approved successfully'
            );
        } catch (\Exception $e) {
            return $this->apiError(
                'Failed to approve content idea',
                500,
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Reject a content idea.
     */
    public function reject(string $id)
    {
        try {
            $idea = $this->contentIdeaService->getIdeaById($id);
            if (! $idea) {
                return $this->apiError('Content idea not found', 404);
            }
            $this->contentIdeaService->reject($idea);

            return $this->apiResponse(
                new ContentIdeaResource($idea),
                'Content idea rejected successfully'
            );
        } catch (\Exception $e) {
            return $this->apiError(
                'Failed to reject content idea',
                500,
                ['error' => $e->getMessage()]
            );
        }
    }
}
