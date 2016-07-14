@extends('layout')

@section('content')
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Date</th>
            <th>XP</th>
            <th>Concept</th>
            <th>Imagen</th>
        </tr>
        </thead>
        <tbody>
        @foreach($logs as $log)
            <tr>
                <th scope="row">{{ $log->created_at }}</th>
                <td>{{ $log->xp }}</td>
                <td>{!!  $log->concept !!}</td>
                <td><a data-toggle="modal" data-target="#modal{{ $log->picture_id }}"><i class="glyphicon glyphicon-eye-open"></i></a></td>
                <div class="modal fade" id="modal{{ $log->picture_id }}" tabindex="-1" role="dialog"
                     aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                            aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="modalLabel">Imagen</h4>
                            </div>
                            <div class="modal-body">
                                <img src="{{ $log->img_url}}"/>
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

    {!! $logs->links() !!}
@endsection