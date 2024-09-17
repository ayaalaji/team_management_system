<?php

namespace App\Http\Controllers\Api;

use App\Models\Project;
use Illuminate\Http\Request;
use App\Services\ProjectService;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProjectRequest;

class ProjectController extends Controller
{
    use ApiResponseTrait;

    protected $projectService;

    public function __construct(ProjectService $projectService)
    {
        $this->projectService = $projectService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created project.
     * @param ProjectRequest $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(StoreProjectRequest $request)
    {
        $validatedData = $request->validated();

        $project = $this->projectService->addProject($validatedData);
        
        if ($project === false) {
            return $this->errorResponse('Unauthorized: Only admins can create projects', 403); 
        }
        if (isset($validatedData['users']) && is_array($validatedData['users'])) {
            $this->projectService->addProjectUsers($project, $validatedData['users']);
        } else {
            return $this->errorResponse('User data is missing or invalid', 400);
        }

        return $this->successResponse($project, 'Project created successfully', 201);
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        //
    }
}
