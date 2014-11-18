<ul class="breadcrumb">
	<li class="active">
    <?php $current_section = Which::section(); ?>
		<i class="ace-icon  fa fa-{{ $current_section->icon }} home-icon"></i>
		<a href="{{URL::route('cms.' . $current_section->alias)}}">
            {{ $current_section->title }}
        </a>
	</li>
</ul><!-- .breadcrumb -->
