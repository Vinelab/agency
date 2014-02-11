<ul class="breadcrumb">
	<li class="active">
		<i class="icon-{{ $cms_sections['current']->icon }} home-icon"></i>
		<a href="{{URL::route('cms.' . $cms_sections['current']->alias)}}">
            {{ $cms_sections['current']->title }}
        </a>
	</li>
</ul><!-- .breadcrumb -->