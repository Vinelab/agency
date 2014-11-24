<?php
    $content_section = Which::section();
    $subsections = $content_section->children;
?>
@section("head")
    @parent
    {{HTML::style(Cdn::asset('/css/content/posts.css'))}}




@stop
@section('content')

    <div>
        @if (Auth::hasPermission('create'))
            <div class="row">
                <a href="{{ URL::route('cms.content.posts.create') }}" class="btn btn-primary">
                    <span class="icon-plus"></span>
                    {{Lang::get('posts/form.new_post')}}
                </a>
            </div>
        @endif
        <ul id='sub-section-list'>
            @foreach($subsections as $section)
                <li class='sub-section-items'>
                    <a href="{{URL::route('cms.content.show',$section->alias)}}">
                        <div class='btn btn-app btn-primary sections-button'>
                            {{$section->title}}
                        </div>
                    </a>
                </li>
            @endforeach


        </ul>
    </div>
@stop

@section('scripts')
    @parent
        {{HTML::script(Cdn::asset('/cms/js/content/index.js'))}}


@stop

@include('cms.layout.master')
