<?php

namespace App\Http\Controllers\Api;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Services\TaskService;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;

class TaskController extends Controller
{
    use ApiResponseTrait;
    protected $taskService;
    public function __construct(TaskService $taskService)
    {
        $this->taskService=$taskService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        
    }

    /**
     * Store a newly created task.
     * @param StoreTaskRequest $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(StoreTaskRequest $request)
    {
        $validatedData = $request->validated();
        $task = $this->taskService->addtask($validatedData);
        return $this->successResponse($task,'You created task Successfully',201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        //
    }
}
