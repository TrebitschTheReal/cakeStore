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
            <div class="card mt-3">
                <div class="card-header">Torta felvétele</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Neve') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('Leírása') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="text" class="form-control" name="email" value="{{ old('email') }}" required autocomplete="email">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Szükséges alapanyag') }}</label>

                            <div class="col-md-6">
                                <select class="form-control" name="" id="">
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Mennyiség') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="number" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
