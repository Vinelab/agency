@section('content')
	<div class="col-xs-12">

        @if ($admin_permissions->has('create'))
        <div class="row">
            <a href="{{ URL::route('cms.post.create') }}" class="btn btn-primary">
                <span class="icon-plus"></span>
                New Post
            </a>
        </div>
        @endif

        <div class="space-4"></div>

        <div class="row">
            <ul>
                
                @foreach($posts as $post)
                    <li>
                        <div class="dd-handle">
                            <img src="{{$post['thumbnail']}}">
                            {{HTML::link(URL::route('cms.post.show',$post['data']->id),$post['data']->title)}}
                            <div class="pull-right action-buttons">

                                @if ($admin_permissions->has('update'))

                                    <a class="blue" href="{{URL::route('cms.post.edit',$post['data']->id)}}">
                                        <i class="icon-pencil bigger-130"></i>
                                    </a>
                                @endif
                                
                                @if ($admin_permissions->has('delete'))
                                    <a class="red" href="{{URL::route('cms.post.destroy',$post['data']->id)}}">
                                        <i class="icon-trash bigger-130"></i>
                                    </a>
                                @endif
                                
                            </div>
                        </div>

                    </li>
                @endforeach
            </ul>

        </div>

    </div>
@stop

@section('scripts')
    @parent

@stop

@include('cms.layout.master')