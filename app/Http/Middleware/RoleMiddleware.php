<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class RoleMiddleware
{
   /**
    * Handle an incoming request.
    *
    * @param \Illuminate\Http\Request $request
    * @param \Closure $next
    * @param string|null $guard
    * @return mixed
    */


   /*
    * $role_one, $role_two .. stb paraméterek a web.php-ban megadott roleok amik itt kiértékelésre kerülnek:
    * 'Route::middleware(['role:admin,manager'])'
    * Egy paraméter kötelező, a másik két paraméter ill. a permission lehet null is, vagyis nem kötelező.
    *
    * A jelenlegi megoldás nem túl dinamikus, mivel annyi paramétert kell felvenni ehhez a middlewarehez, ahány roleal
    * dolgozunk, de a roleok nagyobb csoportok, és 4-5 darabnál nem szokás többet használni belőlük.
    *
    * Ha $requestben szereplő user nem rendelkezik a megfelelő role-al, akkor 404-es hibával megszakad a folyamat.
    * A 404-es hiba biztonságtechnikai kérdés. Ha a 'támadó' releváns információt kap vissza a végrehajtott kísérleteiből,
    * akkor ezáltal kiszolgáltatjuk a rendszerünknek, mivel pontosan tudná milyen hibát váltott ki. Így legfeljebb találgathat.
    */
   public function handle($request, Closure $next, $role_one, $role_two = null, $role_three = null, $permission = null)
   {
      /*
       * Ha guestként érkezik a request (tehát nem érkezik user paraméter a $requestben)
       */
      if (!$request->user()) {
         abort(404);
      }

      if (!$request->user()->hasRole($role_one) &&
         !$request->user()->hasRole($role_two) &&
         !$request->user()->hasRole($role_three)
      ) {

         abort(404);

      }

      if ($permission !== null && !$request->user()->can($permission)) {

         abort(404);
      }

      return $next($request);

   }
}
