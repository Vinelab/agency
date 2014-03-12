
@section("head")
    @parent
    {{HTML::style("/css/content/posts.css")}}

@stop
@section('content')
	<div>
        @if ($admin_permissions->has('create'))
            <div class="row">
                <a href="{{ URL::route('cms.post.create') }}" class="btn btn-primary">
                    <span class="icon-plus"></span>
                    {{Lang::get('posts/form.new_post')}}
                </a>
            </div>
        @endif
        <div class="space-4"></div>
        <div class="tabbable">
            <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">

                @foreach($sub_sections as $sub_section)
                    <li>
                        <a href="{{URL::route('cms.content.show',$sub_section->alias)}}">{{$sub_section->title}}</a>
                    </li>
                @endforeach                
            </ul>     
        </div>
    </div>
@stop

@section('scripts')
    @parent
        {{HTML::script("/cms/js/content/index.js")}}

        
@stop

@include('cms.layout.master')