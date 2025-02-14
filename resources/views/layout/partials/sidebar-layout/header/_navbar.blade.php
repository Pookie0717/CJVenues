<!--begin::Navbar-->
<div class="app-navbar flex-shrink-0">
    <!--begin::User menu-->
	<div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
        <!--begin::Menu wrapper-->
        <form method="post" action="{{ route('set-tenant') }}" id="tenant-form">
            @csrf
            <select class="form-select form-select-transparent" name="tenant" id="tenant" data-placeholder="Select an organization">
                <option>Select an Organisation</option>
               @php
                $currentUser = auth()->user();
                $tenants = \App\Models\Tenant::leftJoin('tenants as t', 'tenants.parent_id', '=', 't.id')->whereHas('users', function ($query) use ($currentUser) {
                    $query->where('user_id', $currentUser->id);
                })
                ->select('tenants.id', DB::raw('CONCAT(CASE WHEN t.name IS NULL THEN "" ELSE CONCAT(t.name, " - ") END, tenants.name) AS name'))
                ->orderBy('name')->get();
               @endphp
                @foreach ($tenants as $tenant)
                    <option value="{{ $tenant->id }}" {{ session('current_tenant_id') == $tenant->id ? 'selected' : '' }}>
                        {{ $tenant->name }}
                    </option>
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
        
        // Check if the current_tenant_id is set in the session
        if ({!! json_encode(session()->has('current_tenant_id')) !!}) {
            var currentTenantId = {!! json_encode(session('current_tenant_id')) !!};
            
            // Find the option with a matching value
            for (var i = 0; i < tenantSelect.options.length; i++) {
                if (tenantSelect.options[i].value == currentTenantId) {
                    // Select the option with a matching value
                    tenantSelect.options[i].selected = true;
                    break; // Exit the loop once a match is found
                }
            }
            
            // Trigger the change event to submit the form
            //tenantSelect.dispatchEvent(new Event('change'));
        } else {
            // Select the first option
            tenantSelect.options[1].selected = true;
            
            // Trigger the change event to submit the form
            tenantSelect.dispatchEvent(new Event('change'));
            
            // Make an Axios POST request to set the session value
            var firstOptionValue = tenantSelect.options[1].value;
            
            axios.post('{{ route('set-tenant') }}', {
                tenant: firstOptionValue
            })
            .then(function (response) {
                // Handle the success response if needed
                console.log('change and selected first');
            })
            .catch(function (error) {
                // Handle errors if needed
            });
        }

        // Listen for the change event
        tenantSelect.addEventListener('change', function () {
            // Make an Axios POST request to set the session value
            var selectedTenantValue = tenantSelect.value;
            
            axios.post('{{ route('set-tenant') }}', {
                tenant: selectedTenantValue
            })
            .then(function (response) {
                // Handle the success response if needed
                console.log('change and selected');
            })
            .catch(function (error) {
                // Handle errors if needed
            });
            
            // Submit the form
            document.getElementById('tenant-form').submit();
        });
    });
</script>


