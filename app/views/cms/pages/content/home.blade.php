@section("head")
    @parent

@stop
@section('content')
	<div class="col-sm-6">
        <ol class="dd-list">
			@foreach($sections as $section)
				<li class="dd-item" data-id="1">
					<div class="dd-handle">
						{{HTML::link(URL::route('cms.content.show',$section->alias),$section->title)}}
					</div>
				</li>
			@endforeach
			

		</ol>
    </div>
@stop

@section('scripts')
    @parent
        {{HTML::script("/cms/js/content/index.js")}}

        
@stop

@include('cms.layout.master')