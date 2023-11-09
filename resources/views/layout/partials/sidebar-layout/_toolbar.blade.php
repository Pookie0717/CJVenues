<!--begin::Toolbar-->
<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
	<!--begin::Toolbar container-->
	<div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
		@include(config('settings.KT_THEME_LAYOUT_DIR').'/partials/sidebar-layout/_page-title')
		<!--begin::Actions-->
		<div class="d-flex align-items-center gap-2 gap-lg-3">
		@if(request()->route()->getName() === 'quotes.show')
                <!-- HTML to include only in the 'quotes.show' view 
                <button type="button" class="btn btn-primary" data-kt-quote-id="{{ $quote->id }}" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_quote" data-kt-action="update_row">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        Edit Quote
                </button>
        	-->
	        <!--begin::Action-->
	        <a class="btn btn-primary" href="{{ route('quotes.showPublic', $hashedId) }}">Share Link</a>
	        <!--end::Action-->
            	@endif
		</div>
		<!--end::Actions-->
	</div>
	<!--end::Toolbar container-->
</div>
<!--end::Toolbar-->
