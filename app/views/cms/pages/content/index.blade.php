@section("head")
    @parent

@stop
@section('content')

    
	<div class="col-sm-6">
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
                                        <li>
                                            {{HTML::link(URL::route('cms.post.show',$post->id),$post->title)}}
                                            {{HTML::link(URL::route('cms.post.destroy',$post->id),'X')}}
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