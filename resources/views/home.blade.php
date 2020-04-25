@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Sikeres belépés!</div>

                <div class="card-body">
                    @role('admin', 'manager', 'reg')
                        <div class="text-center h4 alert alert-success" role="alert">
                            Üdvözlünk {{$user->name}} !
                        </div>
                    @endrole
                </div>
            </div>
        </div>
    </div>

{{--    @foreach($recipes as $cake)
        {{ $cake->name }}

        @foreach($cake->required_ingredients as $ingredient)
            <p>{{$ingredient->name}}</p>
            <p>{{$ingredient->pivot->ingredient_quantity}}</p>
            <p>{{$ingredient->pivot->ingredient_price}}</p>
        @endforeach
    @endforeach--}}


</div>
@endsection
