@if (Session::has('errors'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">
                <i class="icon-remove"></i>
            </button>

            <ul class="list-unstyled">
                @foreach (Session::get('errors') as $error)
                    <li>
                        <strong>
                            <i class="icon-remove"></i>
                        </strong>

                        {{$error}}
                    </li>
                @endforeach
            </ul>
        </div>
@endif

@if (Session::has('warnings'))
    @foreach (Session::get('warnings') as $warning)
        <div class="alert alert-warning">
            <button type="button" class="close" data-dismiss="alert">
                <i class="icon-warning"></i>
            </button>

            <strong>
                <i class="icon-remove"></i>
            </strong>
            {{$warning}}
        </div>
    @endforeach
@endif

@if (Session::has('success'))
    @foreach (Session::get('success') as $message)
        <div class="alert alert-success">
            <button type="button" class="close" data-dismiss="alert">
                <i class="icon-remove"></i>
            </button>

            <strong>
                <i class="icon-ok"></i>
            </strong>
            {{$message}}
        </div>
    @endforeach
@endif