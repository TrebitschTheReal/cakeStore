@extends('layouts.app')

@section('content')
   <div class="container">
      <div class="row justify-content-center">
         <div class="col">
            <h1 class="text-center">Üdvözlünk a CakeStore oldalán!</h1>
            <hr class="">
            <p class="text-center">Kérjük regisztrálj a felületre annak használatához, vagy ha már rendelkezel fiókkal, akkor lépj
               be!</p>
            <div class="mt-5 alert alert-info">
               <div class="col-7 mx-auto">
                  <p class="font-weight-bold h5 text-center">A webalkalmazás demo üzemmódban fut!</p>
                  <p>Regisztrációt követően rögtön admin jogosultságot kapsz, amivel a oldal összes funkcióját elérheted
                     és kipróbálhatod.</p>
                  <p>Ez egy recept építő / alapanyag managelő alkalmazás, ahol a bejelentkezést követően:</p>
                  <ul class="text-justify font-italic">
                     <li>létrehozhatsz, törölhetsz, módosíthatsz recepteket, alapanyagokat</li>
                     <li>az alapanyagokat értelemszerűen kiszerelés / ár szerint is rögzítheted</li>
                     <li>interaktív keresővel listázhatod a recepteket</li>
                     <li>egy recept elkészítési költségét alapanyagra és mennyiségre lebontva láthatod</li>
                  </ul>
                  <p class="font-weight-bold float-left">Az adatbázis tartalma minden óra 00. percében törlődik és visszaáll alapértelmezett értékekre, tehát nyugodtan módosíts, törölj bármit amit szeretnél.</p>
                  <p class="text-center">Jó szórakozást :)</p>
               </div>
            </div>
         </div>
      </div>
   </div>
@endsection
