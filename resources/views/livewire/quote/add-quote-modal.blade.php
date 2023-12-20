<div class="modal fade" id="kt_modal_add_quote" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-850px">
        <div class="modal-content ">
            <div class="modal-header" id="kt_modal_add_quote_header">
                <h2 class="fw-bold">Add Quote</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body scroll-y mx-5 mx-xl-10 my-5">
                <form class="form" novalidate="novalidate" id="kt_modal_add_quote_form" class="form" wire:submit.prevent="submit">
                    <!--begin::Group-->
                    <div>

                        <div class="flex-column" data-kt-stepper-element="content">
                             
                             <!--begin::Input group-->
                             <div class="fv-row mb-10">
                                <label for="contactSelect" class="form-label">Select Contact:</label>
                                <select class="form-select" id="contactSelect" wire:model="contact_id">
                                    <option value="">Select a contact</option>
                                    @foreach ($filteredContacts as $contact)
                                        <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Input group-->
                        </div>

            <!--begin::Step 1-->
            <div class="flex-column" data-kt-stepper-element="content">
                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <label for="eventSelect" class="form-label">Select Event:</label>
                                    <select class="form-select" id="eventNameSelect" wire:model="eventName" wire:change="loadEventTypes">
                                        <option value="">Select an event</option>
                                        <option value="wedding">Wedding</option>
                                <option value="birthday">Birthday Party</option>
                                <option value="summer">Summer Party</option>
                                <option value="corporate">Corporate Event</option>
                                <!-- Add more options as needed -->
                                    </select>
                </div>
                <!--end::Input group-->

                 <!--begin::Input group-->
                 <div class="fv-row mb-10">
                    <label for="eventSelect" class="form-label">Select Event Type:</label>
                    <select class="form-select" id="eventSelect" wire:model="event_type">
                        <option value="">Select an event</option>
                        @foreach ($eventTypes as $event)
                            <option value="{{ $event->id }}">{{ $event->event_name }}</option>
                        @endforeach
                    </select>
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <label for="people" class="form-label">How many people will attend?</label>
                    <input type="number" wire:model.defer="people" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Number of people"
                    id="people" min="{{$selectedEvent? $selectedEvent->min_people: 0}}" max="{{$selectedEvent? $selectedEvent->max_people: 0}}"
                    />

                </div>
                <!--end::Input group-->

                <!--begin::Step 1-->
                <div class="flex-column mb-10" data-kt-stepper-element="content">
                    <!--begin::Input group-->
                    <div class="fv-row mb-10">

                        <div class="row">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2" for="date_from_picker_input">Date From</label>
                                <div class="input-group log-event" id="date_from_picker_basic" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                    <input id="date_from_picker_input" type="text"  wire:model.defer="date_from" class="form-control" data-td-target="#date_from_picker_basic"/>
                                    <span class="input-group-text" data-td-target="#date_from_picker_basic" data-td-toggle="datetimepicker">
                                        <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                </div>
                                @error('date_from')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2" for="date_to_picker_input">Date To</label>
                                <div class="input-group" id="date_to_picker_basic" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                    <input id="date_to_picker_input" type="text"  wire:model.defer="date_to" class="form-control" data-td-target="#date_to_picker_basic"/>
                                    <span class="input-group-text" data-td-target="#date_to_picker_basic" data-td-toggle="datetimepicker">
                                        <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                    </span>
                                </div>
                                @error('date_to')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!--end::Input group-->

                    <!-- Add this code inside your Blade template -->
                    @foreach ($time_ranges as $date => $time_range)
                        <div class="fv-row mb-10 d-none">
                            <div class="row">
                                <div class="col">
                                    <label class="required fw-semibold fs-6 mb-2">Time From ({{ $date }})</label>
                                    <div class="input-group" id="time_from_picker_basic_{{ $loop->index }}" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                        <input id="time_from_picker_input_{{ $loop->index }}" type="text" wire:model.defer="time_ranges.{{ $date }}.time_from" class="form-control" data-td-target="#time_from_picker_{{ $loop->index }}"/>
                                        <span class="input-group-text" data-td-target="#time_from_picker_basic_{{ $loop->index }}" data-td-toggle="datetimepicker">
                                            <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                        </span>
                                    </div>
                                    @error("time_ranges.$date.time_from")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col">
                                    <label class="required fw-semibold fs-6 mb-2">Time To ({{ $date }})</label>
                                    <div class="input-group" id="time_to_picker_basic_{{ $loop->index }}" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                        <input id="time_to_picker_input_{{ $loop->index }}" type="text" wire:model.defer="time_ranges.{{ $date }}.time_to" class="form-control" data-td-target="#time_to_picker_{{ $loop->index }}"/>
                                        <span class="input-group-text" data-td-target="#time_to_picker_basic_{{ $loop->index }}" data-td-toggle="datetimepicker">
                                            <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                        </span>
                                    </div>
                                    @error("time_ranges.$date.time_to")
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <!--end::Input group-->

                             <!--begin::Input group-->
                             <div class="fv-row mb-10">
                                <label for="eventSelect" class="form-label">Select Event Type:</label>
                                <select class="form-select" id="eventSelect" wire:model="event_type">
                                    <option value="">Select an event</option>
                                    @foreach ($filteredEventTypes as $event)
                                        <option value="{{ $event->id }}">{{ $event->event_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <label for="people" class="form-label">How many people will attend?</label>
                                <input type="number" wire:model.defer="people" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Number of people"
                                id="people" min="{{$selectedEvent? $selectedEvent->min_people: 0}}" max="{{$selectedEvent? $selectedEvent->max_people: 0}}"
                                />

                            </div>
                            <!--end::Input group-->

                            <!--begin::Step 1-->
                            <div class="flex-column mb-10" data-kt-stepper-element="content">
                                <!--begin::Input group-->
                                <div class="fv-row mb-10">
                                    <div class="row">
                                                <div class="col">
                                                    <label class="required fw-semibold fs-6 mb-2">Date From</label>
                                                    <div class="input-group" id="date_from_picker_basic" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                                        <input id="date_from_picker_input" type="text"  wire:model.defer="date_from" class="form-control" data-td-target="#date_from_picker"/>
                                                        <span class="input-group-text" data-td-target="#date_from_picker" data-td-toggle="datetimepicker">
                                                            <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                                        </span>
                                                    </div>
                                                    @error('date_from')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>

                                                <div class="col">
                                                    <label class="required fw-semibold fs-6 mb-2">Date To</label>
                                                    <div class="input-group" id="date_to_picker_basic" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                                        <input id="date_to_picker_input" type="text"  wire:model.defer="date_to" class="form-control" data-td-target="#date_to_picker"/>
                                                        <span class="input-group-text" data-td-target="#date_to_picker" data-td-toggle="datetimepicker">
                                                            <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                                        </span>
                                                    </div>
                                                    @error('date_to')
                                                        <span class="text-danger">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                                </div>
                                </div>
                                <!--end::Input group-->

                                <!-- Add this code inside your Blade template -->
                                @foreach ($time_ranges as $date => $time_range)
                                    <div class="fv-row mb-10 d-none">
                                        <div class="row">
                                            <div class="col">
                                                <label class="required fw-semibold fs-6 mb-2">Time From ({{ $date }})</label>
                                                <div class="input-group" id="time_from_picker_basic_{{ $loop->index }}" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                                    <input id="time_from_picker_input_{{ $loop->index }}" type="text" wire:model.defer="time_ranges.{{ $date }}.time_from" class="form-control" data-td-target="#time_from_picker_{{ $loop->index }}"/>
                                                    <span class="input-group-text" data-td-target="#time_from_picker_{{ $loop->index }}" data-td-toggle="datetimepicker">
                                                        <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                                    </span>
                                                </div>
                                                @error("time_ranges.$date.time_from")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>

                                            <div class="col">
                                                <label class="required fw-semibold fs-6 mb-2">Time To ({{ $date }})</label>
                                                <div class="input-group" id="time_to_picker_basic_{{ $loop->index }}" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                                    <input id="time_to_picker_input_{{ $loop->index }}" type="text" wire:model.defer="time_ranges.{{ $date }}.time_to" class="form-control" data-td-target="#time_to_picker_{{ $loop->index }}"/>
                                                    <span class="input-group-text" data-td-target="#time_to_picker_{{ $loop->index }}" data-td-toggle="datetimepicker">
                                                        <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                                    </span>
                                                </div>
                                                @error("time_ranges.$date.time_to")
                                                    <span class="text-danger">{{ $message }}</span>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mx-10">
                                        <label class="fw-semibold fs-6 mb-4 pb-5">@if(sizeof($time_ranges) !== 1){{ $date }}@endif</label>
                                        <div id="event_time_slider_{{ $loop->index }}" class="my-4"></div>
                                    </div>
                                @endforeach

                            </div>
                            <!--begin::Step 1-->

                            <div class="fv-row mb-10">
                                <div class="row">
                                    <!-- Buffer Time Before the Event Start -->
                                    <div class="col">
                                        <label for="buffer_time_before" class="form-label">Buffer Time Before the Event Start:</label>
                                        <input type="number" id="buffer_time_before"
                                        @if($selectedEvent && $selectedEvent->duration_type === "hours")
                                        min="{{($selectedEvent->min_buffer_before)? ($buffer_time_unit === "days"? floor($selectedEvent->min_buffer_before / 8): ($buffer_time_unit === "hours"? $selectedEvent->min_buffer_before: 0)): 0}}" 
                                        max="{{($selectedEvent->max_buffer_before)? ($buffer_time_unit === "days"? floor($selectedEvent->max_buffer_before / 8): ($buffer_time_unit === "hours"? $selectedEvent->max_buffer_before: 0)): 0}}" 
                                        @elseif($selectedEvent && $selectedEvent->duration_type === "days")
                                        min="{{($selectedEvent->min_buffer_before)? ($buffer_time_unit === "days"? $selectedEvent->min_buffer_before: ($buffer_time_unit === "hours"? $selectedEvent->min_buffer_before * 8: 0)): 0}}" 
                                        max="{{($selectedEvent->max_buffer_before)? ($buffer_time_unit === "days"? $selectedEvent->max_buffer_before: ($buffer_time_unit === "hours"? $selectedEvent->max_buffer_before * 8: 0)): 0}}" 
                                        @else
                                        min="0" max="0"
                                        @endif
                                        wire:model.defer="buffer_time_before" class="form-control form-control-solid" placeholder="Buffer Time" />
                                        @error('buffer_time_before')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Buffer Time After the Event End -->
                                    <div class="col">
                                        <label for="buffer_time_after" class="form-label">Buffer Time After the Event End:</label>
                                        <input type="number" id="buffer_time_after"
                                        @if($selectedEvent && $selectedEvent->duration_type === "hours")
                                        min="{{($selectedEvent->min_buffer_after)? ($buffer_time_unit === "days"? floor($selectedEvent->min_buffer_after / 8): ($buffer_time_unit === "hours"? $selectedEvent->min_buffer_after: 0)): 0}}" 
                                        max="{{($selectedEvent->max_buffer_after)? ($buffer_time_unit === "days"? floor($selectedEvent->max_buffer_after / 8): ($buffer_time_unit === "hours"? $selectedEvent->max_buffer_after: 0)): 0}}" 
                                        @elseif($selectedEvent && $selectedEvent->duration_type === "days")
                                        min="{{($selectedEvent->min_buffer_after)? ($buffer_time_unit === "days"? $selectedEvent->min_buffer_after: ($buffer_time_unit === "hours"? $selectedEvent->min_buffer_after * 8: 0)): 0}}" 
                                        max="{{($selectedEvent->max_buffer_after)? ($buffer_time_unit === "days"? $selectedEvent->max_buffer_after: ($buffer_time_unit === "hours"? $selectedEvent->max_buffer_after * 8: 0)): 0}}" 
                                        @else
                                        min="0" max="0"
                                        @endif
                                        wire:model.defer="buffer_time_after" class="form-control form-control-solid" placeholder="Buffer Time"/>
                                        @error('buffer_time_after')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Buffer Time Unit -->
                                    <div class="col">
                                        <label for="buffer_time_unit" class="form-label">Buffer Time Unit:</label>
                                        <select class="form-select" id="buffer_time_unit" wire:model="buffer_time_unit">
                                            <option value="">Select Unit</option>
                                            <option value="hours">Hours</option>
                                            <option value="days">Days</option>
                                        </select>
                                        @error('buffer_time_unit')
                                            <span class="text-danger">{{ $message }}</span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <label for="selectedVenueId" class="form-label">Select Venue:</label>
                                            <select class="form-select" wire:model="selectedVenueId" id="selectedVenueId">
                                                <option value="">Select a Venue</option>
                                                @foreach ($filteredVenues as $venue)
                                                    <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                                                @endforeach
                                            </select>
                            </div>
                            <!--end::Input group-->

                            <!--begin::Input group-->
                            <div class="fv-row mb-10">
                                <label for="areaSelect" class="form-label">Select Areas:</label>
                                <select class="form-select" id="areaSelect" wire:model="area_id">
                                    <option value="">Select an area</option>
                                    @foreach ($filteredAreas as $area)
                                        <option value="{{ $area->id }}">{{ $area->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <!--end::Input group-->
                            
                        </div>
                        <!--begin::Step 1-->

                        <!--begin::Step 1-->
                        <div class="flex-column" data-kt-stepper-element="content">
                            <!--begin::Input group-->
                            <!-- Loop through each option and render based on kind -->
                            @foreach ($options as $option)
                                <div class="fv-row mb-10">
                                    <!-- For 'yes_no', show a select dropdown with Yes and No options -->
                                    @if($option->type === 'yes_no')
                                        <label for="option{{ $option->id }}" class="form-label">{{ $option->name }}:</label>
                                        <select class="form-select" wire:change="updateSelectedOption({{ $option->id }}, $event.target.value)">
                                            <option value="">Select Yes/No</option>
                                            <option value="yes">Yes</option>
                                            <option value="no">No</option>
                                        </select>
                                    @endif

                                    <!-- For 'check', show checkboxes for each value -->
                                    @if($option->type === 'check')
                                        <label class="form-label">{{ $option->name }}:</label>
                                        @foreach(explode('|', $option->values) as $value)
                                            <div>
                                                <input type="checkbox" wire:change="updateCheckboxOption({{ $option->id }}, '{{ $value }}', $event.target.checked)">
                                                <label>{{ $value }}</label>
                                            </div>
                                        @endforeach
                                    @endif

                                    <!-- For 'radio', show radio buttons for each value -->
                                    @if($option->type === 'radio')
                                        <label class="form-label mb-5">{{ $option->name }}:</label>
                                        @foreach(explode('|', $option->values) as $value)
                                            <div class="form-check form-check-custom form-check-solid mb-5">
                                                <input class="form-check-input" type="radio"  wire:change="updateSelectedOption({{ $option->id }}, '{{ $value }}')" id="selectedOptions{{ $option->id }}" name="selectedOptions{{ $option->id }}"/>
                                                <label class="form-check-label" for="selectedOptions{{ $option->id }}">
                                                    {{ $value }}
                                                </label>
                                            </div>
                                        @endforeach
                                    @endif

                                    <!-- For 'dropdown', show dropdown for each value -->
                                    @if($option->type === 'dropdown')
                                        <label for="option{{ $option->id }}" class="form-label">{{ $option->name }}:</label>
                                        <select class="form-select" wire:change="updateSelectedOption({{ $option->id }}, $event.target.value)">
                                            @foreach(explode('|', $option->values) as $value)
                                                <option value="{{$value}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                    @endif

                                    <!-- For 'number', show number input -->
                                    @if($option->type === 'number')
                                        <label for="option{{ $option->id }}" class="form-label">{{ $option->name }}:</label>
                                        <input type="number" wire:change="updateSelectedOption({{ $option->id }}, $event.target.value)" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Number"/>
                                    @endif

                                    <!-- For 'number', show number input -->
                                    @if($option->type === 'hidden')
                                        <label for="option{{ $option->id }}" class="form-label">{{ $option->name }}:</label>
                                        <input type="hidden" wire:change="updateSelectedOption({{ $option->id }}, $event.target.value)" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Number"/>
                                    @endif

                                    <!-- For 'logic', show number input -->
                                    @if($option->type === 'logic')
                                        <input type="hidden" wire:model="selectedOptions.{{ $option->id }}" class="form-control form-control-solid mb-3 mb-lg-0" value="{{ $option->value }}" />
                                    @endif

                                </div>
                            @endforeach
                            <!--end::Input group-->
                            <div class="fv-row mb-10">
                                <label for="discountField" class="form-label">Discount:</label>
                                <input type="text" wire:model.defer="discount" class="form-control form-control-solid" placeholder="Enter a number or a percentage (e.g., 20 or 15%)" id="discountField" />
                                @error('discount')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!--begin::Step 1-->
                    </div>
                    <!--end::Group-->

                    <!--begin::Actions-->
                    <div class="d-flex flex-stack justify-content-center ">

                        <!--begin::Wrapper-->
                        <div class="text-center pt-5">
                            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">Discard</button>
                            <button type="submit" id="submit_button" class="btn btn-primary" @if(sizeof($time_ranges) === 0 || !$selectedEvent) disabled @endif>
                                <span class="indicator-label" wire:loading.remove wire:target="submit">Submit</span>
                                <span class="" wire:loading wire:target="submit">
                                    Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                            </button>
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Actions-->
                </form>
            </div>
        </div>
    </div>
</div>
@push('scripts')

<script>

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

    function setupSlider(event) {
        Object.keys(event.detail.timeRanges).forEach((date, index) => {

            let slider = document.querySelector("#event_time_slider_" + index);

            if(slider.noUiSlider) {
                slider.noUiSlider.destroy();
            }

            noUiSlider.create(slider, {
                start: [
                    convertTimeToDecimal(event.detail.timeRanges[date]["time_from"]),
                    convertTimeToDecimal(event.detail.timeRanges[date]["time_to"]),
                ],
                connect: true,
                step: 0.5,
                tooltips: [
                    true,
                    true, 
                ],
                range: {
                    "min": convertTimeToDecimal(event.detail.selectedEvent?event.detail.selectedEvent["opening_time"]: "00:00"),
                    "max": convertTimeToDecimal(event.detail.selectedEvent?event.detail.selectedEvent["closing_time"]: "23:30")
                },
                format: {
                    to: customSliderTooltip,
                    from: Number
                },
            });

            slider.noUiSlider.updateOptions({
                start: [
                    convertTimeToDecimal(event.detail.timeRanges[date]["time_from"]), 
                    convertTimeToDecimal(event.detail.timeRanges[date]["time_to"]),
                ]
            });

            slider.noUiSlider.on("change", function (values, handle) {
                const maxDuration = Number(event.detail.selectedEvent?event.detail.selectedEvent["max_duration"]: 24);
                const minDuration = Number(event.detail.selectedEvent?event.detail.selectedEvent["min_duration"]: 24);
                console.log(maxDuration, minDuration)
                const duration = convertTimeToDecimal(values[1]) - convertTimeToDecimal(values[0]);
                if(handle) {
                    if(duration < minDuration) values[1] = convertDecimalToTime(convertTimeToDecimal(values[0]) + minDuration);
                    if(duration > maxDuration) values[1] = convertDecimalToTime(convertTimeToDecimal(values[0]) + maxDuration);
                } else {
                    if(duration < minDuration) values[0] = convertDecimalToTime(Math.max(convertTimeToDecimal(values[1]) - minDuration, 0));
                    if(duration > maxDuration) values[0] = convertDecimalToTime(Math.max(convertTimeToDecimal(values[1]) - maxDuration, 0));
                }
                Livewire.emit('update_time_range', {index, date, values});
                slider.noUiSlider.updateOptions({
                    start: [
                        convertTimeToDecimal(values[0]), 
                        convertTimeToDecimal(values[1]),
                    ]
                });
            });
        })
    }

    document.addEventListener('livewire:load', function () {
        window.addEventListener('date-range-updated', event => {
            setupSlider(event);
        })
    });

</script>

<script>

    var fromDateEl = document.getElementById('date_from_picker_basic');
    var fromInput = document.getElementById('date_from_picker_input');
    var toInput = document.getElementById('date_to_picker_input');

    var peopleInput = document.getElementById('people');
    var bufferBeforeInput = document.getElementById('buffer_time_before');
    var bufferAfterInput = document.getElementById('buffer_time_after');
    var bufferUnitInput = document.getElementById('buffer_time_unit');
    var bufferTimeUnitSelect = document.getElementById('buffer_time_unit');
    var submitBtn =  document.getElementById('submit_button');

    function isDateFormat(str) {
        const dateFormatRegex = /^(0[1-9]|[1-2][0-9]|3[0-1])-(0[1-9]|1[0-2])-\d{4}$/;
        return dateFormatRegex.test(str);
    }   

    const linked1 = new tempusDominus.TempusDominus(fromDateEl, {
        display: {
            viewMode: "calendar",
            components: {
                decades: true,
                year: true,
                month: true,
                date: true,
                hours: false,
                minutes: false,
                seconds: false
            }
        },
        localization: {
            locale: "us",
            startOfTheWeek: 1,
            format: "dd-MM-yyyy"
        }
    });

    const linked2 = new tempusDominus.TempusDominus(document.getElementById('date_to_picker_basic'), {
        useCurrent: false,
        display: {
            viewMode: "calendar",
            components: {
                decades: true,
                year: true,
                month: true,
                date: true,
                hours: false,
                minutes: false,
                seconds: false
            }
        },
        localization: {
            locale: "us",
            startOfTheWeek: 1,
            format: "dd-MM-yyyy"
        }
    });

    fromDateEl.addEventListener(tempusDominus.Namespace.events.change, function (e) {
        linked2.updateOptions({
            restrictions: {
                minDate: e.detail.date,
            },
        });

        if(fromInput.value !== "" && toInput.value !== "" && isDateFormat(fromInput.value) && isDateFormat(toInput.value)) {
            const [d1, m1, y1] = fromInput.value.split("-");
            const [d2, m2, y2] = toInput.value.split("-");
            const t1 = new Date(`${y1}-${m1}-${d1}`).getTime();
            const t2 = new Date(`${y2}-${m2}-${d2}`).getTime();
            if(t1 > t2) toastr.warning('Date From setting is incorrect!');
            Livewire.emit('update_date_range', [fromInput.value, toInput.value]);
        } else {
            submitBtn.disabled = true;
        }
    });

    const subscription = linked2.subscribe(tempusDominus.Namespace.events.change, (e) => {
        linked1.updateOptions({
            restrictions: {
                maxDate: e.date,
            },
        });
        if(toInput.value !== "" && fromInput.value !== "" && isDateFormat(toInput.value) && isDateFormat(fromInput.value)) {
            const [d1, m1, y1] = fromInput.value.split("-");
            const [d2, m2, y2] = toInput.value.split("-");
            const t1 = new Date(`${y1}-${m1}-${d1}`).getTime();
            const t2 = new Date(`${y2}-${m2}-${d2}`).getTime();
            if(t1 > t2) toastr.warning('Date To setting is incorrect!');
            Livewire.emit('update_date_range', [fromInput.value, toInput.value]);
        } else {
            submitBtn.disabled = true;
        }
    });

    peopleInput.addEventListener('change', function () {
        if(this.value == "") {
            toastr.warning('People setting is incorrect!');
        } else if(Number(this.value) > Number(this.max)) {
            toastr.warning('People setting is incorrect!');
            this.value = this.max;
            @this.set('people', this.value);
        } else if(Number(this.value) < Number(this.min)) {
            toastr.warning('People setting is incorrect!');
            this.value = this.min;
            @this.set('people', this.value);
        }
    });

    bufferBeforeInput.addEventListener('change', function () {
        if(this.value == "") {
            toastr.warning('Buffer Time Before the Event Start setting is incorrect!');
        } else if(Number(this.value) > Number(this.max)) {
            toastr.warning('Buffer Time Before the Event Start setting is incorrect!');
            this.value = this.max;
            @this.set('buffer_time_before', this.value);
        } else if(Number(this.value) < Number(this.min)) {
            toastr.warning('Buffer Time Before the Event Start setting is incorrect!');
            this.value = this.min;
            @this.set('buffer_time_before', this.value);
        }
    });

    bufferAfterInput.addEventListener('change', function () {
        
        if(this.value == "") {
            toastr.warning('Buffer Time After the Event End setting is incorrect!');
        } else if(Number(this.value) > Number(this.max)) {
            toastr.warning('Buffer Time After the Event End setting is incorrect!');
            this.value = this.max;
            @this.set('buffer_time_after', this.value);
        } else if(Number(this.value) < Number(this.min)) {
            toastr.warning('Buffer Time After the Event End setting is incorrect!');
            this.value = this.min;
            @this.set('buffer_time_after', this.value);
        }
    });

    document.addEventListener('livewire:load', function () {
        window.addEventListener('date-range-updated', event => {
            setupSlider(event);
        })
    });
    
    document.addEventListener('livewire:load', function () {
        window.addEventListener('buffer-time-unit-updated', event => {
            const bufferTimeUnit = event.detail.value;
            const selectedEvent = event.detail.selectedEvent;
            if(selectedEvent && selectedEvent["duration_type"] === "hours") {
                bufferBeforeInput.min = (selectedEvent['min_buffer_before'])? (bufferTimeUnit === "days"? Math.floor(selectedEvent['min_buffer_before'] / 8): (bufferTimeUnit === "hours"? selectedEvent['min_buffer_before']: 0)): 0;
                bufferBeforeInput.max = (selectedEvent['max_buffer_before'])? (bufferTimeUnit === "days"? Math.floor(selectedEvent['max_buffer_before'] / 8): (bufferTimeUnit === "hours"? selectedEvent['max_buffer_before']: 0)): 0;
            } else if(selectedEvent && selectedEvent["duration_type"] === "days") {
                bufferBeforeInput.min = (selectedEvent['min_buffer_before'])? (bufferTimeUnit === "days"? selectedEvent['min_buffer_before']: (bufferTimeUnit === "hours"? selectedEvent['min_buffer_before'] * 8: 0)): 0;
                bufferBeforeInput.max = (selectedEvent['max_buffer_before'])? (bufferTimeUnit === "days"? selectedEvent['max_buffer_before']: (bufferTimeUnit === "hours"? selectedEvent['max_buffer_before'] * 8: 0)): 0; 
            } else {
                bufferBeforeInput.min = 0; bufferBeforeInput.max = 0
            }
            if(selectedEvent && selectedEvent["duration_type"] === "hours") {
                bufferAfterInput.min = (selectedEvent['min_buffer_after'])? (bufferTimeUnit === "days"? Math.floor(selectedEvent['min_buffer_after'] / 8): (bufferTimeUnit === "hours"? selectedEvent['min_buffer_after']: 0)): 0;
                bufferAfterInput.max = (selectedEvent['max_buffer_after'])? (bufferTimeUnit === "days"? Math.floor(selectedEvent['max_buffer_after'] / 8): (bufferTimeUnit === "hours"? selectedEvent['max_buffer_after']: 0)): 0;
            } else if(selectedEvent && selectedEvent["duration_type"] === "days") {
                bufferAfterInput.min = (selectedEvent['min_buffer_after'])? (bufferTimeUnit === "days"? selectedEvent['min_buffer_after']: (bufferTimeUnit === "hours"? selectedEvent['min_buffer_after'] * 8: 0)): 0;
                bufferAfterInput.max = (selectedEvent['max_buffer_after'])? (bufferTimeUnit === "days"? selectedEvent['max_buffer_after']: (bufferTimeUnit === "hours"? selectedEvent['max_buffer_after'] * 8: 0)): 0; 
            } else {
                bufferAfterInput.min = 0; bufferAfterInput.max = 0
            }
            bufferBeforeInput.dispatchEvent(new Event('change'));
            bufferAfterInput.dispatchEvent(new Event('change'));
        })
    });

    
    bufferTimeUnitSelect.addEventListener('change', function() {
        @this.set('buffer_time_unit', this.value);
    })
</script>

@endpush