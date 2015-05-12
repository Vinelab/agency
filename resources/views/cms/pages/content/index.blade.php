<?php
?>
@section("head")
    @parent
    {{HTML::style("/css/content/posts.css")}}

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
        <div class="space-4"></div>
        @if($sub_sections->count() > 0)
                <ul id="sub-section-list">

                    @foreach($sub_sections as $sub_section)
                        <li class="sub-section-items">
                            <a href="{{URL::route('cms.content.show',$sub_section->alias)}}" class="btn btn-app sections-button  {{$sub_section->color}}_color">
                                {{$sub_section->title}}
                            </a>
                        </li>
                    @endforeach
                </ul>
        @endif
    </div>
@stop

@section('scripts')
    @parent
        {{HTML::script("/cms/js/content/index.js")}}


@stop

@include('cms.layout.master')
