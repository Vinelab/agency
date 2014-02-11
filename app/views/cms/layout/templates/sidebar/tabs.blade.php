
<ul class="nav nav-list">
	@foreach ($cms_sections['accessible'] as $section)

		<li {{ ($section->alias === $cms_sections['current']->alias) ? 'class=active' : null }} >
			<a href="{{URL::route('cms.' . $section->alias)}}">
				<i class="icon-{{ $section->icon }}"></i>
				<span class="menu-text"> {{ $section->title }} </span>
			</a>

			@if ($section->is_fertile and count($section->sections) > 0)
				<ul class="submenu">
					@foreach ($section->sections as $sub_section)
					<li>
						<a href={{ URL::route('cms.' . $section->alias .'.show', $sub_section->alias) }}>
							{{ $sub_section->title }}
						</a>
					</li>
					@endforeach
				</ul>
			@endif

		</li>

	@endforeach
</ul>

<!--
<ul class="nav nav-list">
	<li class="active">
		<a href="{{URL::route('cms.dashboard')}}">
			<i class="icon-dashboard"></i>
			<span class="menu-text"> Dashboard </span>
		</a>
	</li>

	<li>
		<a href="{{URL::route('cms.artists')}}">
			<i class="icon-star"></i>
			<span class="menu-text"> Artists </span>
		</a>
	</li>

	<li>
		<a href="{{URL::route('cms.content')}}">
			<i class="icon-rss"></i>
			<span class="menu-text"> Content </span>
		</a>
	</li>

	<li>
		<a href="{{URL::route('cms.audience')}}">
			<i class="icon-group"></i>
			<span class="menu-text"> Audience </span>
		</a>
	</li>

	<li>
		<a href="{{URL::route('cms.administration')}}">
			<i class="icon-list"></i>
			<span class="menu-text"> Administration </span>
		</a>
	</li>

</ul>
-->
<div class="sidebar-collapse" id="sidebar-collapse">
	<i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
</div>