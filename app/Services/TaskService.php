<?php
namespace App\Services;

use Exception;
use App\Models\Task;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class TaskService {
    
    public function addtask(array $data)
    {
        try{
            $user = Auth::user();
            return Task::create([
                'title'=>$data['title'],
                'description'=>$data['description'],
               
                'priority'=>$data['priority'],
                'user_id' => (int)$data['user_id'],
                'added_by' => $user->role, //who is added the task
            ]);
        }catch(Exception $e){
            Log::error('Error creating Task: ' . $e->getMessage());
            throw new Exception('ther is something wrong'. $e->getMessage());
        }
    }
}