@if(Auth::check())
    <footer class="footer text-xs-center">
        <div class="container">
            <span class="text-muted"><small><em>Miembros:</em> <strong>{{ $members_count }}</strong> | <em>Imágenes:</em> <strong>{{ $pictures_count }}</strong> | <em>Votos:</em> <strong>{{ $votes_count }}</strong> |  <em>YLD:</em> <strong>{{ $global_yes_percent }}</strong>% - <em>NO:</em> <strong>{{ $global_no_percent }}</strong>% | <em>En cola:</em> <strong>{{ $pictures_queue_count }}</strong></small></span>
        </div>
    </footer>
@endif