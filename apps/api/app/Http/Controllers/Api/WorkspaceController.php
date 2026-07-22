
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Domain\Workspace\Services\WorkspaceService;
use App\Domain\Workspace\Repositories\EloquentWorkspaceRepository;
use App\Domain\Workspace\Repositories\EloquentWorkspaceMemberRepository;
use App\Domain\Authentication\Repositories\EloquentUserRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class WorkspaceController extends Controller
{
    protected ;
    protected ;
    protected ;
    protected ;

    public function __construct(
        WorkspaceService ,
        EloquentWorkspaceRepository ,
        EloquentWorkspaceMemberRepository ,
        EloquentUserRepository 
    ) {
        ->workspaceService = ;
        ->workspaceRepository = ;
        ->workspaceMemberRepository = ;
        ->userRepository = ;
    }

    /**
     * Display a listing of the workspaces for the authenticated user.
     */
    public function index(Request )
    {
         = ->user()->getId()->toString();
         = ->workspaceRepository->findByOwnerId();
        
        return response()->json(, 200);
    }

    /**
     * Store a newly created workspace in storage.
     */
    public function store(Request )
    {
         = Validator::make(->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if (->fails()) {
            return response()->json(->errors(), 400);
        }

        try {
             = ->user()->getId()->toString();
            
             = ->workspaceService->createWorkspace(
                ->name,
                ->description ?? '',
                
            );

            // Add the owner as a member with owner role
            ->workspaceMemberRepository->save(
                \App\Domain\Workspace\Entities\WorkspaceMember::fromArray([
                    'workspace_id' => ->getId()->toString(),
                    'user_id' => ,
                    'role' => 'owner'
                ])
            );

            return response()->json([
                'message' => 'Workspace created successfully',
                'workspace' => 
            ], 201);
        } catch (\Exception ) {
            return response()->json([
                'message' => 'Failed to create workspace',
                'error' => ->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified workspace.
     */
    public function show(string )
    {
         = ->workspaceRepository->findById();
        
        if (!) {
            return response()->json([
                'message' => 'Workspace not found'
            ], 404);
        }

        // Check if user has access to this workspace
         = \Illuminate\Support\Facades\Auth::user()?->getId()->toString();
        if (!->canBeAccessedBy(\App\Domain\Authentication\Entities\User::fromArray([
            'id' => 
        ]))) {
            return response()->json([
                'message' => 'Unauthorized access to workspace'
            ], 403);
        }

        return response()->json(, 200);
    }

    /**
     * Update the specified workspace in storage.
     */
    public function update(Request , string )
    {
         = Validator::make(->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|nullable|string',
        ]);

        if (->fails()) {
            return response()->json(->errors(), 400);
        }

        try {
             = ->workspaceRepository->findById();
            
            if (!) {
                return response()->json([
                    'message' => 'Workspace not found'
                ], 404);
            }

            // Check if user has access to this workspace
             = \Illuminate\Support\Facades\Auth::user()?->getId()->toString();
            if (!->canBeManagedBy(\App\Domain\Authentication\Entities\User::fromArray([
                'id' => 
            ]))) {
                return response()->json([
                    'message' => 'Unauthorized to update workspace'
                ], 403);
            }

            if (->has('name')) {
                ->setName(->name);
            }
            
            if (->has('description')) {
                ->setDescription(->description);
            }

            ->workspaceRepository->save();

            return response()->json([
                'message' => 'Workspace updated successfully',
                'workspace' => 
            ], 200);
        } catch (\Exception ) {
            return response()->json([
                'message' => 'Failed to update workspace',
                'error' => ->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified workspace from storage.
     */
    public function destroy(string )
    {
        try {
             = ->workspaceRepository->findById();
            
            if (!) {
                return response()->json([
                    'message' => 'Workspace not found'
                ], 404);
            }

            // Check if user has access to this workspace
             = \Illuminate\Support\Facades\Auth::user()?->getId()->toString();
            if (!->canBeManagedBy(\App\Domain\Authentication\Entities\User::fromArray([
                'id' => 
            ]))) {
                return response()->json([
                    'message' => 'Unauthorized to delete workspace'
                ], 403);
            }

            ->workspaceRepository->delete();

            return response()->json([
                'message' => 'Workspace deleted successfully'
            ], 200);
        } catch (\Exception ) {
            return response()->json([
                'message' => 'Failed to delete workspace',
                'error' => ->getMessage()
            ], 500);
        }
    }
}

