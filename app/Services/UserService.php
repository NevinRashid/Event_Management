<?php

namespace App\Services;


use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }
    /**
     * Determines the allowed roles according to the current user's role...
     * 
     * @param User $user
     * 
     * @return array 
     */
    private function allowedRoles($user){
        if($user->hasRole('super admin')){
            return ['super admin','admin','organizer','attendee'];
        }
        elseif($user->hasRole('admin')){
            return ['organizer','attendee'];
        }
        else{
            return ['attendee'];
        }
    }

    /**
     * Add new user to the database.
     * 
     * @param array $userdata
     * 
     * @return User $user
     */
    public function createUser(array $data)
    {
        try{
            $allowedRoles=$this->allowedRoles(Auth::user());
            if(in_array($data['role'],$allowedRoles)){
                $data['password'] = Hash::make($data['password']);
                $role = $data['role'];
                unset($data['role']);
                $user = User::create($data);
                $user->assignRole($role);  
                return $user;
            }
            else{
                return response()->json(['error'=>'You are not allowed to add user'], 403);
            }
        }catch(\Throwable $th){

        }    
    }

    /**
     * Get the specified user from the database.
     * 
     * @param User $user
     * 
     * @return User $user
     */
    public function showUser(User $user)
    {
        try{
            $userRole = $user->getRoleNames()->first();
            if(Auth::user()->hasRole('super admin'))
            {
                return $user;
            } 
            elseif(Auth::user()->hasRole('admin') && ($userRole == 'organizer'|| $userRole == 'attendee'))
            {
                return $user;
            }
            elseif(Auth::user()->hasRole('organizer') && $userRole == 'attendee')
            {
                return $user;
            }
            else{
                return response()->json(['error'=>'You are not allowed to show this user'], 403);
            }

        }catch(\Throwable $th){

        }
    }

    /**
     * Get the specified user from the database for login.
     * 
     * @param array $userdata
     * 
     * @return User $user
     */
    public function getUser(array $data)
    {
        try{
            $user = User::query()->where('email', $data['email'])->first();
            return $user;

        }catch(\Throwable $th){

        }
    }

    /**
    * Get users according to the role of the authenticated user.
    *
    * This method fetches users based on the role of the currently authenticated user.
    * - If the user has the "super admin" role, all users will be returned.
    * - If the user has the "admin" role, only users with "organizer" or "attendee" roles will be returned.
    *
    * @return User $usersarray
    * 
    */
    public function getUsers()
    {
        try{
            $user = Auth::user();
            if($user->hasRole('super admin'))
            {
                $users= User::all();
                return $users;
            }
            elseif($user->hasRole('admin')){
                $users= User::role(['attendee','organizer'])->paginate(20);
                return $users;
            }
            elseif($user->hasRole('organizer')){
                $users= User::role('attendee')->paginate(20);
                return $users;
            }
            else{
                return response()->json(['error'=>'You are not allowed to get users'], 403);
            }

        }catch(\Throwable $th){

        }
    }

    /**
     * Update the specified user in the database.
     * 
     * @param array $userdata
     * @param User $user
     * 
     * @return User $user
     */

    public function updateUser(array $data, User $user){
        try{
            $allowedRoles=$this->allowedRoles(Auth::user());
            if(in_array($data['role'],$allowedRoles)){
                $role = $data['role'];
                unset($data['role']);
                $user->update(array_filter($data));
                $user->syncRoles([$role]);
                return $user;
            }
            else{
                return response()->json(['error'=>'You are not allowed to update this user'], 403);
            }
        }catch(\Throwable $th){
            
        }
    }

    /**
     * Delete user from the database.
     * 
     * @param User $user
     * 
     * @return void
     */
    public function deleteUser(User $user)
    {
        try{
            $userRole = $user->getRoleNames()->first();
            if(Auth::user()->hasRole('super admin'))
            {
                $user->delete();
            }
            elseif(Auth::user()->hasRole('admin') && ($userRole == 'organizer'|| $userRole == 'attendee'))
            {
                $user->delete();
            }
            elseif(Auth::user()->hasRole('organizer') && $userRole == 'attendee')
            {
                $user->delete();
            }
            else{
                return response()->json(['error'=>'You are not allowed to delete this user'], 403);
            }
        }catch(\Throwable $th){

        }
    }

    /**
     * Delete user's tokens from the database.
     * 
     * @param Request @request
     * 
     * @return void
     */
    public function deleteUserTokens(Request $request)
    {
        try{
            $request->user()->currentAccessToken()->delete();

        }catch(\Throwable $th){

        }
    }

}
