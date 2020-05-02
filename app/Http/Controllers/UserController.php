<?php

namespace App\Http\Controllers;

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

       //Todo: validálás!!!

/*       $validatorService = new ValidatorService();
       $response = $validatorService->validateModifyUser($input);

       if($response !== true) {
          return response($response, 422);
       }*/


      /*
       * Megvizsgáljuk, hogy új usert hozunk létre, vagy meglévőt módosítunk
       */
       if (isset($input['user']['id'])) {
          $user = User::find($input['user']['id']);
       } else {
          $user = new User();
          /*
           * Az új usernek jelszót is létrehozunk. a str_shuffle megkavarja az alábbi stringet, a bcrypt, pedig encrypteli.
           * Nem szükséges tudnunk mi a jelszó, mivel a user majd kér magának egy újat az 'elfelejtettem a jelszavam' segítségével,
           * ha az admin hozta létre a fiókját. Ha meg regisztrált akkor tudja.
           * Nem tárolunk titkosítatlan jelszót az adatbázisban! A jelszót cska az tudhatja, aki létrehozta.
           */
          $user->password = bcrypt(str_shuffle(
             'abcdeföüóőúűáélÉÁŰÚŐPÜÓghjklmnopqrstuvwxyzABCDEFGHJKLMNOPQRSTUVWXYZ234567890!$%^&!$%^&'));
       }

       $role = Role::find($input['user']['role_id']);

       $user->name = $input['user']['name'];
       $user->email = $input['user']['email'];
       $user->save();

       /*
        * Leválasztjuk a jelenlegi roleokat
        */
       $user->roles()->detach();

       /*
        * Hozzácsatoljuk az új rolet
        */
       $user->roles()->attach($role);
    }
}
