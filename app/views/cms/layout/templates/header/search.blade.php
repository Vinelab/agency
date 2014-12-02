<div class="nav-search" id="nav-search">
	{{ Form::open([
	    'url'    => URL::route('cms.content.search') ,
	    'class'  => 'form-search',
	    'role'   =>'form',
	]) }}
		<span class="input-icon">
				<input name='keyword' type="text" placeholder="Search ..." class="nav-search-input" onKeyPress='handle()' id="nav-search-input" autocomplete="off" />
			<i class="icon-search nav-search-icon"></i>
		</span>
	{{Form::close()}}
</div>