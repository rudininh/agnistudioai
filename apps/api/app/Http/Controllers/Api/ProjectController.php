<?php

namespace App\Http\Controllers\Api;

use App\Domain\Content\Services\ProjectService;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;
use OpenApi\Annotations as OA;

class ProjectController extends ApiBaseController
{
    protected $projectService;

    public function __construct(
        ProjectService $projectService
    ) {
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of projects for a workspace.
     *
     * @OA\Get(
     *     path="/api/v1/workspaces/{workspaceId}/projects",
     *     summary="List projects",
     *     description="Returns a list of projects for the specified workspace",
     *     operationId="listProjects",
     *     tags={"Projects"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="workspaceId",
     *         in="path",
     *         required=true,
     *         description="ID of the workspace",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Projects retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Projects retrieved successfully"),
     *             @OA\Property(property="data", type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/ProjectResource")
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
    public function index(string $workspaceId, Request $request)
    {
        $projects = $this->projectService->getProjectsByWorkspaceId($workspaceId);

        return $this->apiResponse(
            ProjectResource::collection($projects),
            'Projects retrieved successfully'
        );
    }

    /**
     * Store a newly created project in storage.
     *
     * @OA\Post(
     *     path="/api/v1/workspaces/{workspaceId}/projects",
     *     summary="Create project",
     *     description="Creates a new project in the specified workspace",
     *     operationId="createProject",
     *     tags={"Projects"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="workspaceId",
     *         in="path",
     *         required=true,
     *         description="ID of the workspace",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name"},
     *
     *             @OA\Property(property="name", type="string", example="My Project"),
     *             @OA\Property(property="description", type="string", example="A project for my workspace")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Project created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Project created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/ProjectResource")
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
    public function store(string $workspaceId, Request $request)
    {
        try {
            $project = $this->projectService->createProject(
                $request->validated('name'),
                $request->validated('description') ?? '',
                $workspaceId
            );

            return $this->apiResponse(
                new ProjectResource($project),
                'Project created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->apiError(
                'Failed to create project',
                500,
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Display the specified project.
     *
     * @OA\Get(
     *     path="/api/v1/workspaces/{workspaceId}/projects/{projectId}",
     *     summary="Get project",
     *     description="Returns the details of a specific project",
     *     operationId="getProject",
     *     tags={"Projects"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="workspaceId",
     *         in="path",
     *         required=true,
     *         description="ID of the workspace",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="projectId",
     *         in="path",
     *         required=true,
     *         description="ID of the project",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Project retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Project retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/ProjectResource")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Project not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Project not found")
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
    public function show(string $workspaceId, string $projectId)
    {
        $project = $this->projectService->getProjectById($projectId);
        if (! $project) {
            return $this->apiError('Project not found', 404);
        }

        // Optional: Verify that the project belongs to the workspace
        // $workspace = $this->projectService->getWorkspaceById($workspaceId); // We'd need this method
        // For now, we'll trust that the project ID is correct
        return $this->apiResponse(
            new ProjectResource($project),
            'Project retrieved successfully'
        );
    }

    /**
     * Update the specified project in storage.
     *
     * @OA\Put(
     *     path="/api/v1/workspaces/{workspaceId}/projects/{projectId}",
     *     summary="Update project",
     *     description="Updates an existing project",
     *     operationId="updateProject",
     *     tags={"Projects"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="workspaceId",
     *         in="path",
     *         required=true,
     *         description="ID of the workspace",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Parameter(
     *         name="projectId",
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
     *
     *             @OA\Property(property="name", type="string", example="Updated Project Name"),
     *             @OA\Property(property="description", type="string", example="Updated project description")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Project updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Project updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/ProjectResource")
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
     *         description="Project not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Project not found")
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
    public function update(string $workspaceId, Request $request, string $projectId)
    {
        try {
            $project = $this->projectService->getProjectById($projectId);
            if (! $project) {
                return $this->apiError('Project not found', 404);
            }
            // Optional: Verify that the project belongs to the workspace
            // For now, we'll trust that the project ID is correct
            if ($request->has('name')) {
                $project->setName($request->validated('name'));
            }
            if ($request->has('description')) {
                $project->setDescription($request->validated('description'));
            }
            $this->projectService->updateProject($project);

            return $this->apiResponse(
                new ProjectResource($project),
                'Project updated successfully'
            );
        } catch (\Exception $e) {
            return $this->apiError(
                'Failed to update project',
                500,
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Remove the specified project from storage.
     */
    public function destroy(string $workspaceId, string $projectId)
    {
        try {
            $project = $this->projectService->getProjectById($projectId);
            if (! $project) {
                return $this->apiError('Project not found', 404);
            }
            // Optional: Verify that the project belongs to the workspace
            // For now, we'll trust that the project ID is correct
            $this->projectService->deleteProject($project);

            return $this->apiResponse(null, 'Project deleted successfully');
        } catch (\Exception $e) {
            return $this->apiError(
                'Failed to delete project',
                500,
                ['error' => $e->getMessage()]
            );
        }
    }
}
