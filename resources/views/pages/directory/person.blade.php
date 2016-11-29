@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-1 text-center">
                <h2><span class="label @php
                        switch (true) {
                            case ($person->rating > 7 ) :
                                echo "label-info";
                                break;
                            case ($person->rating > 3 ) :
                                echo "label-success";
                                break;
                            case ($person->rating > 0 ) :
                                echo "label-primary";
                                break;
                            case ($person->rating == 0 ) :
                                echo "label-default";
                                break;
                            case ($person->rating > -4 ) :
                                echo "label-warning";
                                break;
                            case ($person->rating < -3 ) :
                                echo "label-danger";
                                break;
                        } @endphp">{{ $person->rating }}</span></h2>
                @if ($person->pictures->count() > 1)
                    ({{ $person->pictures->count() }} pics)
                @else
                    ({{ $person->pictures->count() }} pic)
                @endif
            </div>
            <div class="col-md-5">
                <h1>{{ $person->name }}</h1>
            </div>
        </div>
        <hr>
        <div class="row">
            @include('includes.gallery')
        </div>
        <div class="row text-center">
            {{ $pictures->links() }}
        </div>
    </div>
@endsection