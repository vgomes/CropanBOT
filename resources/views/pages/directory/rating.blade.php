@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row">
            @foreach($people->chunk(($people->count() / 3) + 1) as $items)
            <div class="col-md-4">
                    @foreach($items as $key => $person)
                        <div class="btn-group directory-btn-group">
                            <a href="" class="btn btn-default disabled"><span class="label label-default">{{ $person->pictures->count() }}</span></a>
                            <a class="btn btn-default disabled"><span class="label @php
                                switch (true) {
                                    case ($person->rating >= 9 ) :
                                            echo "label-info";
                                            break;
                                    case ($person->rating >= 7 ) :
                                        echo "label-success";
                                        break;
                                    case ($person->rating >= 5 ) :
                                        echo "label-primary";
                                        break;
                                    case ($person->rating >= 3 ) :
                                        echo "label-warning";
                                        break;
                                    case ($person->rating < 3 ) :
                                        echo "label-danger";
                                        break;
                                }
                                @endphp ">{{ $person->rating }}</span></a>
                            <a href="{{ route('pages.directory.person', ['slug' => $person->slug]) }}" class="btn btn-default">{{ $person->name }}</a>
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    </div>
@endsection