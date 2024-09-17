<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Traits\ApiResponseTrait;
use App\Http\Controllers\Controller;
use App\Http\Resources\AuthResource;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\UpdateUserRequest;

class UserController extends Controller
{
    use ApiResponseTrait;

    protected $userService;
    public function __construct(UserService $userService)
    {
        $this->userService=$userService;
    }
    /**
     * see all users in storage by admin or manager
     * @return /Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $users =$this->userService->getAllUsers();
        if($users == false){
            return $this->successResponse(null,'Unauthorized',400);
        }
        return $this->successResponse(AuthResource::collection($users),'this is all users',200);
    }

    /**
     * Store a newly user in storage.
     * admin only added user
     * @param RegisterRequest $request
     * @return /Illuminate\Http\JsonResponse
     */
    public function store(RegisterRequest $request)
    {
        $validatedData = $request->validated();
        $addUser =$this->userService->addUser($validatedData);
        if($addUser == false){
            return $this->successResponse(null,'Unauthorized',400);
        }
        return $this->successResponse(new AuthResource($addUser),'You created user successfully',201);
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        $userOne =$this->userService->oneUser($user);
        if($userOne == false){
            return $this->successResponse(null,'Unauthorized',400);
        }
        return $this->successResponse(new AuthResource($userOne),'this is your request',200);
    }

    /**
     * Update the specified user in storage.
     * admin just update user
     * @param UpdateUserRequest $request
     * @param User $user
     * @return /Illuminate\Http\JsonResponse
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $validatedData = $request->validated();
        $userUpdate=$this->userService->updateUser($validatedData,$user);
        if($userUpdate == false){
            return $this->successResponse(null,'Unauthorized',400);
        }
        return $this->successResponse(new AuthResource($userUpdate),'You updated user successfully',200);

    }

    /**
     * Remove the specified user from storage.
     * just admin can delete user
     *  @param User $user
     * @return /Illuminate\Http\JsonResponse
     */
    public function destroy(User $user)
    {
        $user = $this->userService->deleteUser($user);
        if($user == false){
            return $this->successResponse(null,'Unauthorized',400);
        }
        return $this->successResponse(null,'',204); 
    }
}
