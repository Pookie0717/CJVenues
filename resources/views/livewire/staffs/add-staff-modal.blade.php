<div class="modal fade" id="kt_modal_add_staff" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_staff_header">
                <h2 class="fw-bold">{{ trans('staff.add_staff') }}</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body flex-center px-5 my-7">
                <form id="kt_modal_add_staff_form" class="form" wire:submit.prevent="submit">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-5" id="kt_modal_add_staff_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_staff_header" data-kt-scroll-wrappers="#kt_modal_add_staff_scroll" data-kt-scroll-offset="300px">
                        
                        <!-- Name -->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('staff.name') }}</label>
                            <input type="text" wire:model.defer="name" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Name"/>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('staff.type') }}</label>
                            <select wire:model.defer="type" name="type" class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="">{{ trans('general.select') }}</option>
                                <option value="waiters">{{ trans('staff.waiters') }}</option>
                                <option value="venue manager">{{ trans('staff.venue_manager') }}</option>
                                <option value="toilet staff">{{ trans('staff.toilet_staff') }}</option>
                                <option value="cleaners">{{ trans('staff.cleaners') }}</option>
                            </select>
                            @error('type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Area, Venue Dropdowns (conditional) -->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('staff.area') }}</label>
                            <select wire:model.defer="area_ids" name="area_ids[]" class="form-select form-select-solid mb-3 mb-lg-0" multiple>
                            @if($parentTanantId[0] !== null)
                                @foreach($venueArea as $area)
                                    <option value="{{ $area['id'] }}">{{ $area['name'] }}</option>
                                @endforeach
                            @else
                                @foreach($venueArea as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            @endif
                            </select>
                            @error('area_ids')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!-- Option, Option values (conditional) -->
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">{{ trans('staff.option') }}</label>
                            <select wire:model="option" name="option" class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="">{{ trans('general.select') }}</option>
                                @foreach($options_arr as $option_item)
                                    <option value="{{ $option_item['id'] }}">{{ $option_item['name'] }}</option>
                                @endforeach
                            </select>
                            @if (!is_array($option))
                                <label class="fw-semibold fs-6 mb-2">{{ trans('staff.option_value') }}</label>
                                <select wire:model.defer="option_value" name="option_value" class="form-select form-select-solid mb-3 mb-lg-0" multiple>
                                    @foreach($option_value_arr as $option_value_arr_item)
                                        <option value="{{ $option_value_arr_item }}">{{ $option_value_arr_item }}</option>
                                    @endforeach
                                </select>
                            @endif
                            @error('option')
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
                        <div class="mb-3">
                            <button type="button" wire:click="addItem()" class="btn btn-primary btn-sm">Add staff</button>
                        </div>
                        <!-- <div class="text-center" wire:click="addItem()">
                            <a href="#" class="btn btn-icon btn-light pulse">
                                <i class="ki-duotone ki-sms fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                <span class="pulse-ring"></span>
                            </a>
                        </div> -->
                        @for($i = 1;$i <= $items_count;$i++)
                            <div class="row fv-row mb-7">
                                <!-- value -->
                                <div class="col mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">{{ trans('staff.from') }}</label>
                                    <input type="number" wire:model.defer="from.{{$i}}" name="from" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="from"/>
                                    @error('from')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col mb-7">
                                    <label class="required fw-semibold fs-6 mb-2">{{ trans('staff.to') }}</label>
                                    <input type="number" wire:model.defer="to.{{$i}}" name="to" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="to"/>
                                    @error('to')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col mb-7">
                                    <label class="fw-semibold fs-6 mb-2">{{ trans('staff.duartion_type') }}</label>
                                    <select wire:model="duration_type.{{$i}}" name="duration_type" class="form-select form-select-solid mb-3 mb-lg-0">
                                        <option value="hour">{{ trans('staff.hours') }}</option>
                                        <option value="day">{{ trans('staff.days') }}</option>
                                        <option value="people">{{ trans('staff.people') }}</option>
                                    </select>
                                    @error('type')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col mb-7 ml-2">
                                    <label class="required fw-semibold fs-6 mb-2">{{ trans('staff.count') }}</label>
                                    <input type="number" wire:model.defer="count.{{$i}}" name="count" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="count"/>
                                    @error('count')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col mb-7 text-end">
                                    <div>
                                        <label class="fw-semibold fs-6 mb-7">{{ trans('') }}</label>
                                    </div>
                                    <button type="button" wire:click="removeItem({{ $i }})" class="btn btn-danger btn-sm">Remove</button>
                                </div>
                            </div>
                        @endfor
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
