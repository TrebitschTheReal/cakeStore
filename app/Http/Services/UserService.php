<?php


namespace App\Http\Services;


use App\Role;
use App\User;

class UserService
{
   public function createOrModifyUser($input) {

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
      //Frissítjük az updated_at oszlopot
      $user->touch();

      //Elmentjük a modellt
      $user->save();

      /*
       * Leválasztjuk a jelenlegi rolet
       */
      $user->roles()->detach();

      /*
       * Hozzácsatoljuk az új rolet
       */
      $user->roles()->attach($role);
   }
}