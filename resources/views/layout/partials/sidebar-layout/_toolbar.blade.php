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
			<div>
				<button class="btn btn-sm btn-success" id="book-quote" data-quote-id="{{ $quote->id }}" style='min-width: 80px;height: 35px'>
					{{ trans('quotes.book') }}
				</button>
				<button class="btn btn-sm btn-primary show-mode" id="edit-quote" data-quote-id="{{ $quote->id }}" style='min-width: 80px;height: 35px'>
					{{ trans('quotes.edit') }}
				</button>
				<button style="display:none" class="btn btn-sm btn-primary edit-mode" id="submit-quote" data-quote-id="{{ $quote->id }}" style='min-width: 80px;height: 35px'>
					{{ trans('quotes.submit') }}
				</button>                                
			</div>
			<!--end::Action-->
			<button class="btn btn-sm btn-light-secondary p-2" id="export-quote" data-quote-id="{{ $quote->id }}" data-quote-number="{{ trans('quotes.title') }} #{{ $quote->quote_number }}v{{ $quote->version }}" onclick='exportPDF("invoice")' style='min-width: 80px;height: 35px;display: flex'>
				<i class="ki-duotone ki-exit-down fs-2" style="padding: 0;color: black;height: 35px"><span class="path1"></span><span class="path2"></span></i>
				<span style='color: black;font-size: 12px'>Invoice</span>
			</button>
			<button class="btn btn-sm btn-light-secondary p-2" id="export-contract" data-quote-id="{{ $quote->id }}" data-quote-number="{{ trans('quotes.title') }} #{{ $quote->quote_number }}v{{ $quote->version }}" onclick='exportPDF("contract")' style='min-width: 80px;height: 35px;display: flex'>
				<i class="ki-duotone ki-exit-down fs-2" style="padding: 0;color: black;height: 35px"><span class="path1"></span><span class="path2"></span></i>
				<span style='color: black;font-size: 12px'>Contract</span>
			</button>
	        <a class="btn btn-primary" style="font-size: 12px;min-width: 80px;height: 35px;padding: 7.5px" href="{{ route('quotes.showPublic', $hashedId) }}" >Share Link</a>
	        <!--end::Action-->
		@endif
		</div>
		<!--end::Actions-->
	</div>
	<!--end::Toolbar container-->
</div>
<!--end::Toolbar-->
