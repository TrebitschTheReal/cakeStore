<?php

namespace App\Http\Controllers;

use App\Http\Services\UserService;
use App\Http\Services\ValidatorService;
use App\Role;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    public function getUserView() {
        return view('users/operationsUser');
    }

    public function getUserList() {
        $users = User::with('roles')->get();
        return response()->json($users);
    }

    public function getRoles() {
       return Role::all();
    }

    public function deleteUser(Request $request) {
       $removableUser = User::find($request->removableId);
       $removableUser->roles()->detach();
       $removableUser->permissions()->detach();
       $removableUser->delete();
    }

    public function modifyUser(Request $request) {
       $input = $request->all();

       $validatorService = new ValidatorService();
       $response = $validatorService->validateUser($input);

       if($response !== true) {
          return response($response, 422);
       }

       $userService = new UserService();
       $userService->createOrModifyUser($input);

    }
}
