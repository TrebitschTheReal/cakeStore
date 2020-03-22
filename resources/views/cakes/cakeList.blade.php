@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card mt-3">
                    <div class="card-header">Cake</div>
                    <div class="card-body">
                        @foreach($cakes as $cake)
                            Torta neve: <h3>{{ $cake->name }}</h3>
                            @foreach($cake->required_ingredients as $ingredient)
                                <p>Alapanyag: {{$ingredient->name}}</p>
                                <p>Mennyi kell: {{$ingredient->pivot->ingredient_quantity}} egység</p>
                                <p>Mennyibe kerül ennyi egység alapanyag: {{$ingredient->pivot->ingredient_price}} Ft</p>
                            @endforeach
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    <cake-list></cake-list>
@endsection
