<div class="modal fade" id="kt_modal_block_venue_area" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_block_venue_area_header">
                <h2 class="fw-bold">{{ trans('areas.addvenuearea') }}</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body px-5 my-7">
                <form id="kt_modal_block_venue_area_form" class="form" wire:submit.prevent="submit">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_block_venue_area_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_block_venue_area_header" data-kt-scroll-wrappers="#kt_modal_block_venue_area_scroll" data-kt-scroll-offset="300px">
                    
                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                                <label for="start_date">{{ trans('areas.startdate') }}</label>
                                <input type="date" class="form-control" id="start_date" name="start_date" wire:model="start_date" required>
                            </div>
                        </div>
                        <div class="col">
                            <div class="form-group">
                                <label for="end_date">{{ trans('areas.enddate') }}</label>
                                <input type="date" class="form-control" id="end_date" name="end_date" wire:model="end_date" required>
                            </div>
                        </div>
                    </div>

                    </div>
                    
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">{{ trans('general.discard') }}</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label" wire:loading.remove wire:target="submit">{{ trans('general.submit') }}</span>
                            <span class="indicator-progress" wire:loading wire:target="submit">
                                {{ trans('general.pleasewait') }}...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
