<ul class="breadcrumb">
	<li class="active">
    <?php $current_section = Which::section(); ?>
		<i class="ace-icon  fa fa-{{ $current_section->icon }} home-icon"></i>

            {{ $current_section->title }}

	</li>
</ul><!-- .breadcrumb -->
