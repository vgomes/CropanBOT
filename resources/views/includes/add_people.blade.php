<div class="row">
    {!! Form::open(['route' => 'pages.tag']) !!}
    <div class="panel">
        <div class="panel-heading">
            Quién sale en la foto?
        </div>
        <div class="panel-body">
            <select name="people[]" id="" multiple="" class="form-control people" data-placeholder="">
                <option></option>
                @foreach($people as $person)
                    <option value="{{ $person->id }}">{{ $person->name }}</option>
                @endforeach
            </select>
            {!! Form::hidden('picture_id', $picture->id) !!}
        </div>
        <div class="panel-footer">
            <button class="btn btn-primary btn-sm">Añadir</button>
        </div>
    </div>
    {!! Form::close() !!}
</div>

@push('scripts')
<script type="text/javascript">
    $(document).ready(function () {
        $('.people').select2({
            tags: "true",
            placeholder: 'Escoge o añade una persona'
        });
    });
</script>
@endpush