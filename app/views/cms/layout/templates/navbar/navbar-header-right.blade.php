<div class="navbar-header pull-right" role="navigation">
	<ul class="nav ace-nav ">
		<li class="light-blue">
			<a data-toggle="dropdown" href="#" class="dropdown-toggle" aria-expanded="false">
				<span class="user-info">
					<small>{{Lang::get('navbar.welcome')}}</small>
					{{ Auth::user()->name }}
				</span>

				<i class="ace-icon fa fa-caret-down"></i>
			</a>

			<ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
				<li>
					<a href="{{URL::route('cms.dashboard.password')}}">
						<i class="icon-cog"></i>
						{{Lang::get('navbar.change_password')}}
					</a>
				</li>

				<li>
					<a href="{{URL::route('cms.dashboard.profile')}}">
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