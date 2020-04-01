@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
{{--    @foreach($cakes as $cake)
        {{ $cake->name }}

        @foreach($cake->required_ingredients as $ingredient)
            <p>{{$ingredient->name}}</p>
            <p>{{$ingredient->pivot->ingredient_quantity}}</p>
            <p>{{$ingredient->pivot->ingredient_price}}</p>
        @endforeach
    @endforeach--}}

    {{$cake->required_ingredients[0]->pivot}}

</div>
@endsection
