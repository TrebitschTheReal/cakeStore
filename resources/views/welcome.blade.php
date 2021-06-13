@extends('layouts.app')

@section('content')
   <div class="container">
      <div class="row justify-content-center text-center">
         <div class="col">
            <h1 class="">Üdvözlünk a CakeStore oldalán!</h1>
            <hr class="">
            <p class="">Kérjük regisztrálj a felületre annak használatához, vagy ha már rendelkezel fiókkal, akkor lépj be!</p>
            <div class="mt-5 alert alert-info">
               <span class="font-weight-bold">A webalkalmazás demo üzemmódban fut!</span><br>
               <span>Regisztrációt követően rögtön admin jogosultságot kapsz, amivel a oldal összes funkcióját elérheted és kipróbálhatod.</span><br>
               <span>Az adatbázist nem tudod tönkretenni, mert minden óra 00. percében visszállnak az alapértelmezett adatok, tehát nyugodtan módosíts, törölj bármit amit szeretnél.</span><br>
               <span class="mt-2">Jó szórakozást :)</span>
            </div>
         </div>
      </div>
   </div>
@endsection
