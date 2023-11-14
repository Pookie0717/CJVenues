<!--begin::sidebar menu-->
<div class="app-sidebar-menu overflow-hidden flex-column-fluid">
	<!--begin::Menu wrapper-->
	<div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
		<!--begin::Menu-->
		<div class="menu menu-column menu-rounded menu-sub-indention px-3 fw-semibold fs-6" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
			<!--begin:Menu item-->
			<div class="menu-item">
				<!--begin:Menu link-->
				<span class="menu-link">
					<span class="menu-icon">{!! getIcon('element-11', 'fs-2') !!}</span>
					<span class="menu-title"><a class="menu-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">Dashboard</a></span>
				</span>
				<!--end:Menu link-->
			</div>
			<!--end:Menu item-->
			<!--begin:Menu item-->
			<div class="menu-item">
				<!--begin:Menu link-->
				<span class="menu-link">
					<span class="menu-icon">{!! getIcon('file', 'fs-2') !!}</span>
					<span class="menu-title"><a class="menu-link {{ request()->routeIs('quotes') ? 'active' : '' }}" href="{{ route('quotes') }}">Quotes</a></span>
				</span>
				<!--end:Menu link-->
			</div>
			<!--end:Menu item-->
			<!--begin:Menu item-->
			<div class="menu-item">
				<!--begin:Menu link-->
				<span class="menu-link">
					<span class="menu-icon">{!! getIcon('book', 'fs-2') !!}</span>
					<span class="menu-title"><a class="menu-link {{ request()->routeIs('bookings') ? 'active' : '' }}" href="{{ route('bookings') }}">Bookings</a></span>
				</span>
				<!--end:Menu link-->
			</div>
			<!--end:Menu item-->
			<!--begin:Menu item-->
			<div class="menu-item">
				<!--begin:Menu link-->
				<span class="menu-link">
					<span class="menu-icon">{!! getIcon('address-book', 'fs-2') !!}</span>
					<span class="menu-title"><a class="menu-link {{ request()->routeIs('contacts') ? 'active' : '' }}" href="{{ route('contacts') }}">Contacts</a></span>
				</span>
				<!--end:Menu link-->
			</div>
			<!--end:Menu item-->
			<!--begin:Menu item-->
			<div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->routeIs('venues.*') || request()->routeIs('areas.*') ? 'here show' : '' }}">
				<!--begin:Menu link-->
				<span class="menu-link">
					<span class="menu-icon">{!! getIcon('abstract-13', 'fs-2') !!}</span>
					<span class="menu-title"><a class="menu-link">Configuration</a></span>
					<span class="menu-arrow"></span>
				</span>
				<!--end:Menu link-->
				<!--begin:Menu sub-->
				<div class="menu-sub menu-sub-accordion">
					<!--begin:Menu item-->
					<div class="menu-item">
						<!--begin:Menu link-->
						<a class="menu-link {{ request()->routeIs('prices') ? 'active' : '' }}" href="{{ route('prices') }}">
							<span class="menu-bullet">
								<span class="bullet bullet-dot"></span>
							</span>
							<span class="menu-title">Prices</span>
						</a>
						<!--end:Menu link-->
					</div>
					<!--end:Menu item-->
					<!--begin:Menu item-->
					<div class="menu-item">
						<!--begin:Menu link-->
						<a class="menu-link {{ request()->routeIs('venues') ? 'active' : '' }}" href="{{ route('venues') }}">
							<span class="menu-bullet">
								<span class="bullet bullet-dot"></span>
							</span>
							<span class="menu-title">Venues</span>
						</a>
						<!--end:Menu link-->
					</div>
					<!--end:Menu item-->
					<!--begin:Menu item-->
					<div class="menu-item">
						<!--begin:Menu link-->
						<a class="menu-link {{ request()->routeIs('areas') ? 'active' : '' }}" href="{{ route('areas') }}">
							<span class="menu-bullet">
								<span class="bullet bullet-dot"></span>
							</span>
							<span class="menu-title">Areas</span>
						</a>
						<!--end:Menu link-->
					</div>
					<!--end:Menu item-->
					<!--begin:Menu item-->
					<div class="menu-item">
						<!--begin:Menu link-->
						<a class="menu-link {{ request()->routeIs('seasons') ? 'active' : '' }}" href="{{ route('seasons') }}">
							<span class="menu-bullet">
								<span class="bullet bullet-dot"></span>
							</span>
							<span class="menu-title">Seasons</span>
						</a>
						<!--end:Menu link-->
					</div>
					<!--end:Menu item-->

					<!--begin:Menu item-->
					<div class="menu-item">
						<!--begin:Menu link-->
						<a class="menu-link {{ request()->routeIs('options') ? 'active' : '' }}" href="{{ route('options') }}">
							<span class="menu-bullet">
								<span class="bullet bullet-dot"></span>
							</span>
							<span class="menu-title">Options</span>
						</a>
						<!--end:Menu link-->
					</div>
					<!--end:Menu item-->
					<div class="menu-item">
						<!--begin:Menu link-->
						<a class="menu-link {{ request()->routeIs('event-types') ? 'active' : '' }}" href="{{ route('event-types') }}">
							<span class="menu-bullet">
								<span class="bullet bullet-dot"></span>
							</span>
							<span class="menu-title">Event Packages</span>
						</a>
						<!--end:Menu link-->
					</div>
				</div>
				<!--end:Menu sub-->
			</div>
			<!--end:Menu item-->
			<!--begin:Menu item-->
			<div class="menu-item pt-5">
				<!--begin:Menu content-->
				<div class="menu-content">
					<span class="menu-heading fw-bold text-uppercase fs-7">System</span>
				</div>
				<!--end:Menu content-->
			</div>
			<!--end:Menu item-->
			<!--begin:Menu item-->
			<div data-kt-menu-trigger="click" class="menu-item menu-accordion {{ request()->routeIs('user-management.*') ? 'here show' : '' }}">
				<!--begin:Menu link-->
				<span class="menu-link">
					<span class="menu-icon">{!! getIcon('profile-user', 'fs-2') !!}</span>
					<span class="menu-title"><a class="menu-link">Users</a></span>
					<span class="menu-arrow"></span>
				</span>
				<!--end:Menu link-->
				<!--begin:Menu sub-->
				<div class="menu-sub menu-sub-accordion">
					<!--begin:Menu item-->
					<div class="menu-item">
						<!--begin:Menu link-->
						<a class="menu-link {{ request()->routeIs('user-management.users.*') ? 'active' : '' }}" href="{{ route('user-management.users.index') }}">
							<span class="menu-bullet">
								<span class="bullet bullet-dot"></span>
							</span>
							<span class="menu-title">Users</span>
						</a>
						<!--end:Menu link-->
					</div>
					<!--end:Menu item-->
					<!--begin:Menu item-->
					<div class="menu-item">
						<!--begin:Menu link-->
						<a class="menu-link {{ request()->routeIs('user-management.roles.*') ? 'active' : '' }}" href="{{ route('user-management.roles.index') }}">
							<span class="menu-bullet">
								<span class="bullet bullet-dot"></span>
							</span>
							<span class="menu-title">Roles</span>
						</a>
						<!--end:Menu link-->
					</div>
					<!--end:Menu item-->
					<!--begin:Menu item-->
					<div class="menu-item">
						<!--begin:Menu link-->
						<a class="menu-link {{ request()->routeIs('organizations') ? 'active' : '' }}" href="{{ route('organizations') }}">
							<span class="menu-bullet">
								<span class="bullet bullet-dot"></span>
							</span>
							<span class="menu-title">Organizations</span>
						</a>
						<!--end:Menu link-->
					</div>
					<!--end:Menu item-->
					<!--begin:Menu item-->
					<div class="menu-item">
						<!--begin:Menu link-->
						<a class="menu-link {{ request()->routeIs('user-management.permissions.*') ? 'active' : '' }}" href="{{ route('user-management.permissions.index') }}">
							<span class="menu-bullet">
								<span class="bullet bullet-dot"></span>
							</span>
							<span class="menu-title">Permissions</span>
						</a>
						<!--end:Menu link-->
					</div>
					<!--end:Menu item-->
				</div>
				<!--end:Menu sub-->
			</div>
			<!--end:Menu item-->
			<!--begin:Menu item-->
			<div class="menu-item">
				<!--begin:Menu link-->
				<span class="menu-link">
					<span class="menu-icon">{!! getIcon('setting-3', 'fs-2') !!}</span>
					<span class="menu-title"><a class="menu-link" href="#">Settings</a></span>
				</span>
				<!--end:Menu link-->
			</div>
			<!--end:Menu item-->
		</div>
		<!--end::Menu-->
	</div>
	<!--end::Menu wrapper-->
</div>
<!--end::sidebar menu-->
