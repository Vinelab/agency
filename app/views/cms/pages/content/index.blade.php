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
                    New Post
                </a>
            </div>
        @endif
        <div class="space-4"></div>
        <div class="tabbable">
            <ul class="nav nav-tabs padding-12 tab-color-blue background-blue" id="myTab4">
                @foreach($section_posts as $key=>$section_post)
               
                    <li {{$key==0?"class='active'":"class=''"}}>
                        <a data-toggle="tab" href="#{{$section_post['sub_section']->alias}}">{{$section_post['sub_section']->title}}</a>
                    </li>
                @endforeach                
            </ul>

            
            <div class="tab-content">
              
                @foreach($section_posts as $key=>$section_post)
                    <div id="{{$section_post['sub_section']->alias}}" class="tab-pane {{$key==0? 'active':''}}">
                            @if(!empty($section_post['posts']))
                                <ul>
                                    @foreach($section_post['posts'] as $post)
                                        <li class="posts-container">
                                            <div class="dd-handle">
                                                <div class="pull-right action-buttons">

                                                    @if ($admin_permissions->has('update'))

                                                        <a class="blue" href="{{URL::route('cms.post.edit',$post['data']->id)}}">
                                                            <i class="icon-pencil bigger-130"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                                <img src="{{$post['thumbnail']}}">
                                                    {{HTML::link(URL::route('cms.post.show',$post['data']->id),$post['data']->title)}}
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                    </div>
                @endforeach

            </div>
        </div>
    </div>
@stop

@section('scripts')
    @parent
        {{HTML::script("/cms/js/content/index.js")}}

        
@stop

@include('cms.layout.master')