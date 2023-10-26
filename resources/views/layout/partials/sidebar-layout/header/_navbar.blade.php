<!--begin::Navbar-->
<div class="app-navbar flex-shrink-0">
    <!--begin::User menu-->
	<div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
        <!--begin::Menu wrapper-->
        <form method="post" action="{{ route('set-tenant') }}" id="tenant-form">
            @csrf
            <select class="form-select form-select-transparent" name="tenant" id="tenant" data-placeholder="Select an organization">
                <option>Select an Organisation</option>
                @foreach (auth()->user()->tenants as $tenant)
                    <option value="{{ $tenant->id }}" {{ session('current_tenant_id') == $tenant->id ? 'selected' : '' }}>
{{ $tenant->name }}</option>
                @endforeach
            </select>
        </form>
		<div class="cursor-pointer symbol symbol-35px ms-1" data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
            @if(Auth::user()->profile_photo_url)
                <img src="{{ \Auth::user()->profile_photo_url }}" class="rounded-3" alt="user" />
            @else
                <div class="symbol-label fs-3 {{ app(\App\Actions\GetThemeType::class)->handle('bg-light-? text-?', Auth::user()->name) }}">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
            @endif
        </div>
        @include('partials/menus/_user-account-menu')
        <!--end::Menu wrapper-->
    </div>
    <!--end::User menu-->
    <!--begin::Header menu toggle-->
	<div class="app-navbar-item d-lg-none ms-2 me-n2" title="Show header menu">
		<div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px" id="kt_app_header_menu_toggle">{!! getIcon('element-4', 'fs-1') !!}</div>
    </div>
    <!--end::Header menu toggle-->
	<!--begin::Aside toggle-->
	<!--end::Header menu toggle-->
</div>
<!--end::Navbar-->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var tenantSelect = document.getElementById('tenant');

        // Check if the current_tenant_id is not set in the session
        if ({!! json_encode(!session()->has('current_tenant_id')) !!}) {
            // Select the first option
            tenantSelect.options[1].selected = true;
            // Trigger the change event to submit the form
            tenantSelect.dispatchEvent(new Event('change'));
        }

        // Listen for the change event
        tenantSelect.addEventListener('change', function () {
            document.getElementById('tenant-form').submit();
        });
    });
</script>


