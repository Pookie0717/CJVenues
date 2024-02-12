<div class="modal fade" id="kt_modal_add_staff" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_staff_header">
                <h2 class="fw-bold">{{ trans('staff.addstaff') }}</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body flex-center  px-5 my-7">
                <form id="kt_modal_add_staff_form" class="form" wire:submit.prevent="submit">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_staff_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_staff_header" data-kt-scroll-wrappers="#kt_modal_add_staff_scroll" data-kt-scroll-offset="300px">
                        
                        <!-- Name -->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('staff.name') }}</label>
                            <input type="text" wire:model="name" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Name"/>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('staff.type') }}</label>
                            <select wire:model="type" name="type" class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="">{{ trans('general.select') }}</option>
                                <option value="waiters">{{ trans('waiters') }}</option>
                                <option value="venue manager">{{ trans('venue manager') }}</option>
                                <option value="toilet staff">{{ trans('toilet staff') }}</option>
                                <option value="cleaners">{{ trans('cleaners') }}</option>
                            </select>
                            @error('type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Area, Venue Dropdowns (conditional) -->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('staff.area') }}</label>
                            <select wire:model.defer="area_ids" name="area_ids[]" class="form-select form-select-solid mb-3 mb-lg-0" multiple>
                                @foreach($venueArea as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                            @error('area_ids')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <script>
                            function limitSelect(element, maxCount) {
                                var selectedCount = 0;
                                for (var i = 0; i < element.options.length; i++) {
                                    if (element.options[i].selected) selectedCount++;
                                    if (selectedCount > maxCount) {
                                        element.options[i].selected = false;
                                        return false;
                                    }
                                }
                            }
                        </script>

                        <div class="row fv-row mb-7">
                            <!-- value -->
                            <div class="col mb-7">
                                <label class="required fw-semibold fs-6 mb-2">{{ trans('staff.value') }}</label>
                                <input type="text" wire:model="value" name="value" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="value"/>
                                @error('value')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
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
