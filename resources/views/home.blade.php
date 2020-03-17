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
                        <p>{{$cake->required_ingredients}}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
