<div class="navbar-header pull-left" role="navigation">
	<ul class="nav ace-nav ">
		<li class="light-blue">
			<a data-toggle="dropdown" href="#" class="dropdown-toggle">
				<!-- add the user image here -->
				<img class="nav-user-photo" src="{{asset('assets/avatars/user.jpg')}}" alt="Jason's Photo" />
				<span class="user-info">
					<small>Welcome,</small>
					<!-- add the username here -->
					{{ Auth::user()->name }}
				</span>
				<i class="icon-caret-down"></i>
			</a>
			<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
				<li>
					<a href="#">
						<i class="icon-cog"></i>
						Settings
					</a>
				</li>

				<li>
					<a href="#">
						<i class="icon-user"></i>
						Profile
					</a>
				</li>

				<li class="divider"></li>
				<li>
					<a href="{{ URL::route('cms.logout') }}">
						<i class="icon-off"></i>
						Logout
					</a>
				</li>
			</ul>
		</li>
	</ul>
</div>