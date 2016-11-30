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
                                    case ($person->pictures->avg('score') > 7 ) :
                                            echo "label-info";
                                            break;
                                    case ($person->pictures->avg('score') > 3 ) :
                                        echo "label-success";
                                        break;
                                    case ($person->pictures->avg('score') > 0 ) :
                                        echo "label-primary";
                                        break;
                                    case ($person->pictures->avg('score') == 0 ) :
                                        echo "label-default";
                                        break;
                                    case ($person->pictures->avg('score') > -4 ) :
                                        echo "label-warning";
                                        break;
                                    case ($person->pictures->avg('score') < -3 ) :
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