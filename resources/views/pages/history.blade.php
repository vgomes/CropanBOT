@extends('layout')

@section('content')
    <table class="table table-striped">
        <thead>
        <tr>
            <th>#</th>
            <th>URL</th>
            <th>Yay</th>
            <th>Nay</th>
            <th>Score</th>
            <th>Published</th>
        </tr>
        </thead>
        <tbody>
        @foreach($pictures as $pic)
            <tr>
                <th scope="row">{{ $pic->id }}</th>
                <td><a data-toggle="modal" data-target="#modal{{ $pic->id }}">{{ $pic->url }}</a></td>
                <td>{{ $pic->yes }}</td>
                <td>{{ $pic->no }}</td>
                <td>{{ $pic->score }}</td>
                <td>
                    @if(!is_null($pic->published_at))
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    @else
                        <span class="glyphicon glyphicon-remove" aria-hidden="true"></span>
                    @endif
                </td>
                <!-- Modal -->
                <div class="modal fade" id="modal{{ $pic->id }}" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="modalLabel">Image</h4>
                            </div>
                            <div class="modal-body">
                                <img src="{{ $pic->url }}"/>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
            </tr>
        @endforeach
        </tbody>
    </table>

    {!! $pictures->links() !!}
@endsection