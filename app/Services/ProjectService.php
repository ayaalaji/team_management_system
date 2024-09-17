<?php
namespace App\Services;

use Exception;
use App\Models\Task;
use App\Models\User;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

class ProjectService{
    public function addProject(array $data)
    {
        try{
            $admin = auth()->user();
            if ($admin->role == 'admin'){
                
                $project = Project::create([
                    'name' => $data['name'],
                    'description' => $data['description'] ?? null,
                ]);
                return $project;
            } else {
                return false;
            }

        }catch (Exception $e) {
           Log::error('Error creating project: ' . $e->getMessage());
           throw new Exception('There is something wrong: ' . $e->getMessage());
        }
    }

    public function addProjectUsers(Project $project, array $userData)
    {
        try {
        // Check if 'users' key exists and is an array
        if (isset($userData) && is_array($userData)) {
            // Attach users to the project with their roles and contribution hours
            foreach ($userData as $user) {
                $userModel = User::find($user['user_id']);
                if ($userModel) {
                    // Attach the user to the project with role and contribution hours
                    $project->users()->attach($userModel->id, [
                        'role' => $user['role'],
                        'contribution_hours' => $user['contribution_hours'] ?? 0,
                    ]);

                    // If tasks are provided, assign them to the user
                    if (isset($user['tasks']) && is_array($user['tasks'])) {
                        foreach ($user['tasks'] as $taskId) {
                            $task = Task::find($taskId);
                            if ($task && $task->user_id == $userModel->id) {
                                $task->save(); 
                            }
                        }
                    }
                }
            }
        }
    } catch (Exception $e) {
        Log::error('Error adding project users: ' . $e->getMessage());
        throw new Exception('There is something wrong: ' . $e->getMessage());
    }
    }
    
}