<ul class="nav nav-list">
	<?php $current_section = Which::section(); ?>
	@foreach (Auth::accessibleSections() as $section)

		<li {{ ($section->alias === $current_section->alias) ? 'class=active' : null }} >
			<a href="{{URL::route('cms.' . $section->alias)}}">
				<i class="menu-icon  fa fa-{{ $section->icon }}"></i>
				<span class="menu-text"> {{ $section->title }} </span>
			</a>

			@if ($section->is_fertile && count($section->children) > 0)
				<ul class="submenu">
					@foreach ($section->children as $sub_section)
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

<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
	<i class="ace-icon fa fa-angle-double-left" data-icon1=" fa fa-double-angle-left" data-icon2=" fa fa-double-angle-right"></i>
</div>
