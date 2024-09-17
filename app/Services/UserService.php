<?php
namespace App\Services;

use Exception;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserService{
    public function getAllUsers()
    {
        try{
            $user = Auth::user();
            if($user->role=='admin'){
                $allUsers = User::all();
                return $allUsers;
            } else{
                return false;
            }
        }catch(Exception $e) {
            Log::error('Error Get all user: ' . $e->getMessage());
            throw new Exception('ther is something wrong'. $e->getMessage());
        }   

    }
    public function addUser(array $data)
    {
        try
        {
            $user =Auth::user();
            if($user->role=='admin'){
                return User::create([
                    'first_name' =>$data['first_name'],
                    'last_name' =>$data['last_name'],
                    'email' =>$data['email'],
                    'password' =>$data['password'],
                    'user_role' =>'user'
                ]);
            }else {
                return false;
            }
        }catch(Exception $e) {
            Log::error('Error Add User: ' . $e->getMessage());
            throw new Exception('ther is something wrong'. $e->getMessage());
        }   
    }
    public function updateUser(array $data,User $user)
    {
        try{
            $userRole =Auth::user();
            if($userRole->role=='admin'){
                $updateUser=array_filter([
                    'first_name' =>$data['first_name'] ?? $user->first_name,
                    'last_name' =>$data['last_name']?? $user->last_name,
                    'email' =>$data['email']?? $user->email,
                    'password' =>$data['password']?? $user->password,
                ]);
                $user->update($updateUser);
                return $user;
            } else{
                return false;
            }
        }catch(Exception $e) {
            Log::error('Error Updated User: ' . $e->getMessage());
            throw new Exception('ther is something wrong'. $e->getMessage());
        }
    }
    public function oneUser(User $user)
    {
        try {
            $currentUser = Auth::user();

            
            if ($currentUser->role == 'admin') {
                return $user; 
            }

           
            if ($currentUser->role == 'user') {
                if ($currentUser->id == $user->id) {
                    return $currentUser; 
                } else {
                    return false;
                }
            }
        } catch (Exception $e) {
            Log::error('Error fetching user information: ' . $e->getMessage());
            throw new Exception('There is something wrong: ' . $e->getMessage());
        }
    }
    public function deleteUser(User $user)
    {
        try{
            $admin =Auth::user();
            if($admin->role=='admin'){
                $user->delete();
                return $user;
            }else{
                return false;
            }
        }catch(Exception $e) {
            Log::error('Error deleted User: ' . $e->getMessage());
            throw new Exception('ther is something wrong'. $e->getMessage());
        }
    }
}