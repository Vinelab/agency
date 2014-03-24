<ul class="breadcrumb">
	<li id="bread-crumb-first">
		<i class="icon-{{ $cms_sections['current']->icon }} home-icon"></i>
		<a href="{{URL::route('cms.' . $cms_sections['current']->alias)}}">
            {{ $cms_sections['current']->title }}
        </a>
	</li>

	@if(isset($parent_sections))
		@foreach($parent_sections as $section)
			<li>
	        	<a href="{{URL::route('cms.content.show',$section->alias)}}">
	            	{{$section->title}}
	        	</a>
        	</li>
		@endforeach	
	@endif

	
	
	
</ul><!-- .breadcrumb -->