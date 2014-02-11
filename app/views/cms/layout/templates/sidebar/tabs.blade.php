
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

<div class="sidebar-collapse" id="sidebar-collapse">
	<i class="icon-double-angle-left" data-icon1="icon-double-angle-left" data-icon2="icon-double-angle-right"></i>
</div>