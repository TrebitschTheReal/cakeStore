<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getUserView() {
        return view('users/operationsUser');
    }

    public function getUserList() {
        $users = User::with('roles')->get();
        return response()->json($users);
    }

    public function deleteUser(Request $request) {
       $removableUser = User::find($request->removableId);
       $removableUser->roles()->detach();
       $removableUser->permissions()->detach();
       $removableUser->delete();
    }
}
