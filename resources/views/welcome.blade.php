@extends('layouts.app')

@section('content')
   <div class="container">
      <div class="row justify-content-center text-center">
         <div class="col">
            <h1 class="">Üdvözlünk a CakeStore oldalán!</h1>
            <hr class="">
            <p class="">Kérjük regisztrálj a felületre annak használatához, vagy ha már rendelkezel fiókkal, akkor lépj
               be!</p>
            <div class="mt-5 alert alert-info">
               <div class="col-7 text-center mx-auto">
                  <p class="font-weight-bold h5">A webalkalmazás demo üzemmódban fut!</p>
                  <p>Regisztrációt követően rögtön admin jogosultságot kapsz, amivel a oldal összes funkcióját elérheted
                     és kipróbálhatod.</p>
                  <p>Ez egy recept építő / alapanyag managelő alkalmazás, ahol a bejelentkezést követően:</p>
                  <ul class="text-justify font-italic">
                     <li>létrehozhatsz, törölhetsz, módosíthatsz recepteket, alapanyagokat</li>
                     <li>az alapanyagokat értelemszerűen kiszerelés / ár szerint is rögzítheted</li>
                     <li>interaktív keresővel listázhatod a recepteket</li>
                     <li>egy recept elkészítési költségét alapanyagra és mennyiségre lebontva láthatod</li>
                  </ul>
                  <p>Az adatbázist nem tudod tönkretenni, mert minden óra 00. percében visszállnak az
                     alapértelmezett adatok, tehát nyugodtan módosíts, törölj bármit amit szeretnél.</p>
                  <p>Jó szórakozást :)</p>
               </div>
            </div>
         </div>
      </div>
   </div>
@endsection
