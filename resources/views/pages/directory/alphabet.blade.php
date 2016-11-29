@extends('layouts.app')

@section('content')
    <div class="container">
        @foreach($people->chunk(3) as $items)
            <div class="row">
                @foreach($items as $key => $people)
                    <div class="col-md-4">
                        <div class="panel">
                            <div class="panel-body">
                                <h4>{{ $key }}</h4>
                            </div>
                            <div class="panel-footer">
                                @foreach($people as $person)
                                    <div class="btn-group directory-btn-group">
                                        <a href="" class="btn btn-default disabled"><span class="label label-default">{{ $person->pictures->count() }}</span></a>
                                        <a class="btn btn-default"><span class="label @php
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
                                            } @endphp">{{ $person->rating }}</span></a>
                                        <a href="{{ route('pages.directory.person', ['slug' => $person->slug]) }}" class="btn btn-default">{{ $person->name }}</a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endforeach
    </div>
@endsection