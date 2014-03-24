<div class="navbar-header pull-right" role="navigation">
	<ul class="nav ace-nav ">
		<li class="light-blue">
			<a data-toggle="dropdown" href="#" class="dropdown-toggle">
				<!-- add the user image here -->
				<span class="user-info">
					<small>{{Lang::get('navbar.welcome')}}</small>
					<!-- add the username here -->
					{{ Auth::user()->name }}
				</span>
				<i class="icon-caret-down"></i>
			</a>
			<ul class="user-menu pull-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
				<li>
					<a href="{{URL::route('cms.administration.password')}}">
						<i class="icon-cog"></i>
						{{Lang::get('navbar.change_password')}}
					</a>
				</li>

				<li>
					<a href="{{URL::route('cms.profile')}}">
						<i class="icon-user"></i>
						{{Lang::get('navbar.profile')}}
					</a>
				</li>

				<li class="divider"></li>
				<li>
					<a href="{{ URL::route('cms.logout') }}">
						<i class="icon-off"></i>
						{{Lang::get('navbar.logout')}}
					</a>
				</li>
			</ul>
		</li>
	</ul>
</div>