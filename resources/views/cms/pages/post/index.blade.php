@section('content')
	<div class="col-xs-12">

        @if (Auth::hasPermission('create'))
        <div class="row">
            <a href="{{ URL::route('cms.content.posts.create') }}" class="btn btn-primary">
                <span class="icon-plus"></span>
                {{Lang::get('posts/form.new_post')}}
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
                            {{HTML::link(URL::route('cms.content.posts.show',$post['data']->slug),$post['data']->title)}}
                            <div class="pull-right action-buttons">

                                @if (Auth::hasPermission('update'))

                                    <a class="blue" href="{{URL::route('cms.content.posts.edit',$post['data']->slug)}}">
                                        <i class="icon-pencil bigger-130"></i>
                                    </a>
                                @endif
                                
                                @if (Auth::hasPermission('delete'))
                                    <a class="red" href="{{URL::route('cms.content.posts.destroy',$post['data']->slug)}}">
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