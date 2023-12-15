<div class="modal fade" id="kt_modal_add_venue_area" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_venue_area_header">
                <h2 class="fw-bold">{{ trans('areas.addvenuearea') }}</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body px-5 my-7">
                <form id="kt_modal_add_venue_area_form" class="form" wire:submit.prevent="submit">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_venue_area_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_venue_area_header" data-kt-scroll-wrappers="#kt_modal_add_venue_area_scroll" data-kt-scroll-offset="300px">
                        
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('areas.name') }}</label>
                            <input type="text" wire:model.defer="name" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ trans('areas.name') }}"/>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('areas.capacity') }}</label>
                            <div class="col">
                            <input type="number" wire:model.defer="capacity_noseating" name="capacity_noseating" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ trans('areas.capacity_noseating') }}"/>
                            @error('capacity_noseating')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>
                            <div class="col">
                            <input type="number" wire:model.defer="capacity_seatingrows" name="capacity_seatingrows" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ trans('areas.capacity_inrows') }}"/>
                            @error('capacity_seatingrows')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>
                            <div class="col">
                            <input type="number" wire:model.defer="capacity_seatingtables" name="capacity_seatingtables" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ trans('areas.capacity_tables') }}"/>
                            @error('capacity_seatingtables')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('areas.venue') }}</label>
                            <select wire:model.defer="venue_id" name="venue_id" class="form-select form-select-solid">
                                <option value="">{{ trans('areas.selectvenue') }}</option>
                                <!-- Populate this with available Venues -->
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                                @endforeach
                            </select>
                            @error('venue_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
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
