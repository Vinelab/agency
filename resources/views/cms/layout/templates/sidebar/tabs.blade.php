<ul class="nav nav-list">
	<?php $current_section = Which::section(); ?>

		{{--
		 * A - if has no children and is not child
		 * B - if has children
		 * C - if has no children and is a child
		 *
		 * array[A/B]
		 * if C has parent A/B >> array[A/B][C]
		 *
		 * foreach A/B
		 *    if A print
		 *    if B print
		 *   if has C then print
		 --}}

	@foreach (Auth::accessibleSections() as $section)

		<li class="{{ ($section->alias === $current_section->alias) ? 'active open' : null }}" >

			{{-- if has children --}}
			@if($section->is_fertile && count($section->children) > 0)
				<a href="#" class="dropdown-toggle">
					<b class="arrow fa fa-angle-down"></b>
					<i class="menu-icon  fa fa-{{ $section->icon }}"></i>
					<span class="menu-text"> {{ $section->title }} </span>
				</a>

				<?php $current_category_alias = (Which::category() ? Which::category()->alias : ''); ?>

				<ul class="submenu">
					@foreach ($section->children as $sub_section)

						<li class="{{ ($sub_section->alias === $current_category_alias) ? 'active' : null }}" >
							<a href={{ URL::route('cms.' . $section->alias) .'?category='.$sub_section->alias  }}>
								{{ $sub_section->title }}
							</a>
						</li>

					@endforeach
				</ul>

			@elseif(is_null($section->parent))
				<a href="{{URL::route('cms.' . $section->alias)}}">
					<i class="menu-icon  fa fa-{{ $section->icon }}"></i>
					<span class="menu-text"> {{ $section->title }} </span>
				</a>
			@endif

		</li>

	@endforeach
</ul>

<div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
	<i class="ace-icon fa fa-angle-double-left" data-icon1=" fa fa-double-angle-left" data-icon2=" fa fa-double-angle-right"></i>
</div>
