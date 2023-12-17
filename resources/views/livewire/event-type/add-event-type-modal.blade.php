<div class="modal fade" id="kt_modal_add_event_type" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_event_type_header">
                <h2 class="fw-bold">{{ trans('events.addeventpackage') }}</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    <!-- Add your close icon here -->
                </div>
            </div>
            <div class="modal-body px-5 my-7">
                <form id="kt_modal_add_event_type_form" class="form" wire:submit.prevent="submit">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_event_type_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_event_type_header" data-kt-scroll-wrappers="#kt_modal_add_event_type_scroll" data-kt-scroll-offset="300px">
                        
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('events.packagename') }}</label>
                            <input type="text" wire:model.defer="event_name" name="event_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ trans('events.packagename') }}"/>
                            @error('event_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row mb-7">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">{{ trans('events.description') }}</label>
                                <input type="text" wire:model.defer="description" name="description" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ trans('events.description') }}"/>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('events.category') }}</label>
                            <select wire:model.defer="selectedEventNames" name="name[]" class="form-select form-select-solid" multiple>
                                <option value="wedding">{{ trans('events.wedding') }}</option>
                                <option value="birthday">{{ trans('events.birthdayparty') }}</option>
                                <option value="summer">{{ trans('events.summerparty') }}</option>
                                <option value="corporate">{{ trans('events.corporateevent') }}</option>
                                <!-- Add more options as needed -->
                            </select>
                            @error('selectedEventNames')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row mb-7">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">{{ trans('events.typicalseating') }}</label>
                                <select wire:model.defer="typical_seating" name="typical_seating" class="form-select form-select-solid">
                                    <option value="">{{ trans('general.select') }}</option>
                                    <option value="noseating">{{ trans('events.seating_noseating') }}</option>
                                    <option value="seatingrows">{{ trans('events.seating_inrows') }}</option>
                                    <option value="seatingtables">{{ trans('events.seating_tables') }}</option>
                                    <!-- Add more options as needed -->
                                </select>
                                @error('typical_seating')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">{{ trans('events.durationtype') }}</label>
                                <select wire:model.defer="duration_type" name="duration_type" class="form-select form-select-solid">
                                    <option value="">{{ trans('general.select') }}</option>
                                    <option value="days">{{ trans('events.duration_days') }}</option>
                                    <option value="hours">{{ trans('events.duration_hours') }}</option>
                                    <!-- Add more options as needed -->
                                </select>
                                @error('duration_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-7">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">{{ trans('events.minduration') }}</label>
                                <input type="number" id="min_duration" wire:model.defer="min_duration" name="min_duration" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ trans('events.minduration') }}"/>
                                @error('min_duration')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">{{ trans('events.maxduration') }}</label>
                                <input type="number" id="max_duration" wire:model.defer="max_duration" name="max_duration" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ trans('events.maxduration') }}"/>
                                @error('max_duration')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-7">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">{{ trans('events.minpeople') }}</label>
                                <input type="number" wire:model.defer="min_people" name="min_people" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ trans('events.minpeople') }}"/>
                                @error('min_people')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">{{ trans('events.maxpeople') }}</label>
                                <input type="number" wire:model.defer="max_people" name="max_people" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ trans('events.maxpeople') }}"/>
                                @error('max_people')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-7">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">{{ trans('events.minbufferbefore') }}</label>
                                <input type="number" wire:model.defer="min_buffer_before" name="min_buffer_before" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ trans('events.minbufferbefore') }}"/>
                                @error('min_buffer_before')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">{{ trans('events.maxbufferbefore') }}</label>
                                <input type="number" wire:model.defer="max_buffer_before" name="max_buffer_before" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ trans('events.maxbufferbefore') }}"/>
                                @error('max_buffer_before')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-7">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">{{ trans('events.minbufferafter') }}</label>
                                <input type="number" wire:model.defer="min_buffer_after" name="min_buffer_after" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ trans('events.minbufferbafter') }}"/>
                                @error('min_buffer_after')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">{{ trans('events.maxbufferafter') }}</label>
                                <input type="number" wire:model.defer="max_buffer_after" name="max_buffer_after" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ trans('events.maxbufferafter') }}"/>
                                @error('max_buffer_after')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('events.venuearea') }}</label>
                            <select wire:model.defer="venue_area_id" name="venue_area_id" class="form-select form-select-solid">
                                <option value="">{{ trans('general.select') }}</option>
                                @foreach($venueAreas as $venueArea)
                                    <option value="{{ $venueArea->id }}">{{ $venueArea->name }}</option>
                                @endforeach
                            </select>
                            @error('venue_area_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <!--begin::Input group-->
                    <div class="fv-row mb-7">
                        <div class="row d-none">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">{{ trans('events.openingtime') }}</label>
                                <div class="input-group" id="opening_time_picker_basic" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                    <input id="opening_time_picker_input" type="text"  wire:model.defer="opening_time" class="form-control" data-td-target="#opening_time_picker"/>
                                    <span class="input-group-text" data-td-target="#opening_time_picker" data-td-toggle="datetimepicker">
                                        <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                </div>
                                @error('opening_time')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">{{ trans('events.closingtime') }}</label>
                                <div class="input-group" id="closing_time_picker_basic" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                    <input id="closing_time_picker_input" type="text"  wire:model.defer="closing_time" class="form-control" data-td-target="#closing_time_picker"/>
                                    <span class="input-group-text" data-td-target="#closing_time_picker" data-td-toggle="datetimepicker">
                                        <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                </div>
                                @error('closing_time')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <label class="required fw-semibold fs-6 mb-4 pb-5">Event Time</label>
                        <div id="event_time_slider" class="my-4"></div>
                    </div>
                    <!--end::Input group-->

                        <div class="row mb-7">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">{{ trans('events.season') }}</label>
                                <select wire:model="selectedSeasons" name="seasons[]" class="form-select form-select-solid" multiple>
                                    @foreach($seasonsList as $season)
                                        <option value="{{ $season->id }}">{{ $season->name }}</option>
                                    @endforeach
                                </select>
                                @error('selectedSeasons')
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
                                {{ trans('general.pleasewait') }} ...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>   

var slider = document.querySelector("#event_time_slider");
var minDurationInput = document.querySelector("#min_duration");
var maxDurationInput = document.querySelector("#max_duration");

function convertDecimalToTime(decimalNumber) {
    if (typeof decimalNumber !== 'number' || decimalNumber < 0 || decimalNumber > 24) {
        return 'Invalid input';
    }
    const tolerance = 0.01
    if(Math.abs(decimalNumber - Math.round(decimalNumber)) < tolerance) decimalNumber = Math.round(decimalNumber)
    const hour = Math.floor(decimalNumber);
    const minute = decimalNumber % 1 < 0.5 + tolerance && decimalNumber % 1 > 0.5 - tolerance ? '30' : '00';
    const formattedHour = hour < 10 ? '0' + hour : hour;
    const formattedTime = `${formattedHour}:${minute}`;
    return formattedTime;
}


function convertTimeToDecimal(timeString) {
    const [hours, minutes] = timeString.split(':');
    const decimalNumber = parseInt(hours, 10) + parseInt(minutes, 10) / 60;
    return decimalNumber;
}


function customSliderTooltip (value) {
  return convertDecimalToTime(value);
}

noUiSlider.create(slider, {
    start: [0, 23.5],
    connect: true,
    step: 0.5,
    tooltips: [true, true],
    range: {
        "min": 0,
        "max": 23.5
    },
    format: {
      to: customSliderTooltip,
      from: Number
    },
});

document.addEventListener('livewire:load', () => {

    window.addEventListener('event-type-range-updated', function(event) {
        slider.noUiSlider.updateOptions({
            start: [convertTimeToDecimal(event.detail.openingTime), convertTimeToDecimal(event.detail.closingTime)]
        });
    })

    slider.noUiSlider.on("change", function (values, handle) {
        Livewire.emit('update_event_type_range', values);
        slider.noUiSlider.updateOptions({
            start: [
                convertTimeToDecimal(values[0]), 
                convertTimeToDecimal(values[1]),
            ]
        });
    });
});


</script>
@endpush
