@extends('layouts.app')

@section('content')
    <div class="container">

        @include('includes.gallery')

        <div class="row text-center">
            {{ $pictures->links() }}
        </div>
    </div>
@endsection
