@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="col-md-8 col-md-offset-2">
            <div class="jumbotron text-center">
                <h1>Cropan Gourmet</h1>
                <br>
                <p class="lead">El club de caballeros más selecto de todo el internec.</p>
                <br>
                <p><a href="{{ route('login.twitter') }}" class="btn btn-lg btn-primary">Entrar</a></p>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    body {
        background: url('images/gentlemen.jpg') no-repeat center center fixed;
        background-size: cover;
    }

    .jumbotron {
        margin-top: 10em;
        padding: 2em 0em;
    }
</style>
@endpush