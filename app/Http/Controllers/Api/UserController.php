<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CreateUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    
    /**
     * This property is used to handle various operations related to users,
     * such as creating, updating.
     *
     * @var UserService
     */
    protected $userService;

    public static function middleware(): array
    {
        return [
            new Middleware('permission:view user', only: ['index','show']),
            new Middleware('permission:create user', only: ['store']),
            new Middleware('permission:edit user', only: ['update']),
            new Middleware('permission:delete user', only: ['destroy']),
        ];
    }

    /**
     * Constructor for the UserController class.
     * 
     * Initializes the $userService property via dependency injection.
     * 
     * @param UserService $userService
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * This method return all users from database.
     * 
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return $this->success($this->userService->getUsers());
        
    }

    /**
     * Register a new User in the database using the UserService via the createUser method
     * passes the validated request data to createUser.
     * 
     * @param CreateUserRequest $request
     * 
     * @return \Illuminate\Http\Response
     */
    public function store(CreateUserRequest $request)
    {
        return $this->success(
            $this->userService->createUser($request->validated()), 'User has been registered successfully',
            201);
    }

    /**
     * Get user from database.
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->success($this->userService->showUser($user));
    }

    /**
     * Update a user in the database using the UserService via the updateUser method.
     * passes the validated request data to updateUser.
     * 
     * @param UpdateUserRequest $request
     * 
     * @param User $user
     * 
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        return $this->success($this->userService->updateUser($request->validated(),$user),'updated successfuly');
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if(Auth::user()->id === $user->id)
        {
            return $this->error('You can not delete yourself');
        }
        $this->userService->deleteUser($user);
        return $this->success(null,'Deleted successfuly',204);
    }
}
