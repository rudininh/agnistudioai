<?php

namespace App\Http\Controllers\Api;

use App\Domain\Authentication\Entities\User;
use App\Domain\Workspace\Services\WorkspaceService;
use App\Http\Requests\StoreWorkspaceRequest;
use App\Http\Requests\UpdateWorkspaceRequest;
use App\Http\Resources\WorkspaceResource;
use OpenApi\Annotations as OA;

class WorkspaceController extends ApiBaseController
{
    protected $workspaceService;

    public function __construct(
        WorkspaceService $workspaceService
    ) {
        $this->workspaceService = $workspaceService;
    }

    /**
     * Display a listing of the workspaces for the authenticated user.
     *
     * @OA\Get(
     *     path="/api/v1/workspaces",
     *     summary="List workspaces",
     *     description="Returns a list of workspaces owned by the authenticated user",
     *     operationId="listWorkspaces",
     *     tags={"Workspaces"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Response(
     *         response=200,
     *         description="Workspaces retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Workspaces retrieved successfully"),
     *             @OA\Property(property="data", type="array",
     *
     *                 @OA\Items(ref="#/components/schemas/WorkspaceResource")
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
    public function index()
    {
        $userId = auth()->user()->getId()->toString();
        $workspaces = $this->workspaceService->getWorkspacesByOwnerId($userId);

        return $this->apiResponse(
            WorkspaceResource::collection($workspaces),
            'Workspaces retrieved successfully'
        );
    }

    /**
     * Store a newly created workspace in storage.
     *
     * @OA\Post(
     *     path="/api/v1/workspaces",
     *     summary="Create workspace",
     *     description="Creates a new workspace for the authenticated user",
     *     operationId="createWorkspace",
     *     tags={"Workspaces"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *             required={"name"},
     *
     *             @OA\Property(property="name", type="string", example="My Workspace"),
     *             @OA\Property(property="description", type="string", example="A workspace for my projects")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=201,
     *         description="Workspace created successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Workspace created successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/WorkspaceResource")
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
    public function store(StoreWorkspaceRequest $request)
    {
        try {
            $userId = $request->user()->getId()->toString();
            $workspace = $this->workspaceService->createWorkspace(
                $request->validated('name'),
                $request->validated('description') ?? '',
                $userId
            );

            // Add the owner as a member with owner role would be handled by the service or event
            // For now, we'll let the service handle it or create an event listener
            return $this->apiResponse(
                new WorkspaceResource($workspace),
                'Workspace created successfully',
                201
            );
        } catch (\Exception $e) {
            return $this->apiError(
                'Failed to create workspace',
                500,
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Display the specified workspace.
     *
     * @OA\Get(
     *     path="/api/v1/workspaces/{id}",
     *     summary="Get workspace",
     *     description="Returns a specific workspace by ID",
     *     operationId="getWorkspace",
     *     tags={"Workspaces"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Workspace ID",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Workspace retrieved successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Workspace retrieved successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/WorkspaceResource")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized access to workspace",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized access to workspace")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Workspace not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Workspace not found")
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
        $workspace = $this->workspaceService->getWorkspaceById($id);
        if (! $workspace) {
            return $this->apiError('Workspace not found', 404);
        }
        // Check if user has access to this workspace
        $userId = auth()->user()->getId()->toString();
        if (! $workspace->canBeAccessedBy(User::fromArray([
            'id' => $userId,
        ]))) {
            return $this->apiError('Unauthorized access to workspace', 403);
        }

        return $this->apiResponse(
            new WorkspaceResource($workspace),
            'Workspace retrieved successfully'
        );
    }

    /**
     * Update the specified workspace in storage.
     *
     * @OA\Put(
     *     path="/api/v1/workspaces/{id}",
     *     summary="Update workspace",
     *     description="Updates an existing workspace",
     *     operationId="updateWorkspace",
     *     tags={"Workspaces"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Workspace ID",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\RequestBody(
     *         required=true,
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="name", type="string", example="Updated Workspace Name"),
     *             @OA\Property(property="description", type="string", example="Updated workspace description")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Workspace updated successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Workspace updated successfully"),
     *             @OA\Property(property="data", ref="#/components/schemas/WorkspaceResource")
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
     *         response=403,
     *         description="Unauthorized to update workspace",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized to update workspace")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Workspace not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Workspace not found")
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
    public function update(UpdateWorkspaceRequest $request, string $id)
    {
        try {
            $workspace = $this->workspaceService->getWorkspaceById($id);
            if (! $workspace) {
                return $this->apiError('Workspace not found', 404);
            }
            // Check if user has access to this workspace
            $userId = auth()->user()->getId()->toString();
            if (! $workspace->canBeManagedBy(User::fromArray([
                'id' => $userId,
            ]))) {
                return $this->apiError('Unauthorized to update workspace', 403);
            }
            if ($request->has('name')) {
                $workspace->setName($request->validated('name'));
            }
            if ($request->has('description')) {
                $workspace->setDescription($request->validated('description'));
            }
            $this->workspaceService->updateWorkspace($workspace);

            return $this->apiResponse(
                new WorkspaceResource($workspace),
                'Workspace updated successfully'
            );
        } catch (\Exception $e) {
            return $this->apiError(
                'Failed to update workspace',
                500,
                ['error' => $e->getMessage()]
            );
        }
    }

    /**
     * Remove the specified workspace from storage.
     *
     * @OA\Delete(
     *     path="/api/v1/workspaces/{id}",
     *     summary="Delete workspace",
     *     description="Deletes a workspace by ID",
     *     operationId="deleteWorkspace",
     *     tags={"Workspaces"},
     *     security={{"sanctum":{}}},
     *
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Workspace ID",
     *
     *         @OA\Schema(type="string")
     *     ),
     *
     *     @OA\Response(
     *         response=200,
     *         description="Workspace deleted successfully",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Workspace deleted successfully")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=403,
     *         description="Unauthorized to delete workspace",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Unauthorized to delete workspace")
     *         )
     *     ),
     *
     *     @OA\Response(
     *         response=404,
     *         description="Workspace not found",
     *
     *         @OA\JsonContent(
     *
     *             @OA\Property(property="success", type="boolean", example=false),
     *             @OA\Property(property="message", type="string", example="Workspace not found")
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
            $workspace = $this->workspaceService->getWorkspaceById($id);
            if (! $workspace) {
                return $this->apiError('Workspace not found', 404);
            }
            // Check if user has access to this workspace
            $userId = auth()->user()->getId()->toString();
            if (! $workspace->canBeManagedBy(User::fromArray([
                'id' => $userId,
            ]))) {
                return $this->apiError('Unauthorized to delete workspace', 403);
            }
            $this->workspaceService->deleteWorkspace($workspace);

            return $this->apiResponse(null, 'Workspace deleted successfully');
        } catch (\Exception $e) {
            return $this->apiError(
                'Failed to delete workspace',
                500,
                ['error' => $e->getMessage()]
            );
        }
    }
}
