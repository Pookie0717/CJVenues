<div class="modal fade" id="kt_modal_add_quote" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content shadow-none">
            <div class="modal-header" id="kt_modal_add_quote_header">
                <h2 class="fw-bold">Add Quote</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body p-10 p-lg-20">
                <!--begin::Stepper-->
                <div class="stepper stepper-pills stepper-column d-flex flex-column flex-lg-row" id="kt_stepper">
                    <div class="d-flex flex-row-auto w-100 w-lg-350px">
                        <div class="stepper-nav mb-10">
                            <!--begin::Step 1-->
                            <div class="stepper-item {{$stepperIndex == 1?'current': ($stepperIndex > 1?'completed': '')}}" data-kt-stepper-element="nav">
                                <!--begin::Wrapper-->
                                <div class="stepper-wrapper d-flex align-items-center">
                                    <!--begin::Icon-->
                                    <div class="stepper-icon w-40px h-40px">
                                        <i class="stepper-check fas fa-check"></i>
                                        <span class="stepper-number">1</span>
                                    </div>
                                    <!--end::Icon-->

                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <div class="stepper-desc">
                                            Contact
                                        </div>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Wrapper-->

                                <!--begin::Line-->
                                <div class="stepper-line h-40px"></div>
                                <!--end::Line-->
                            </div>
                            <!--end::Step 1-->

                            <!--begin::Step 2-->
                            <div class="stepper-item {{$stepperIndex == 2?'current': ($stepperIndex > 2 ?'completed': '')}}" data-kt-stepper-element="nav">
                                <!--begin::Wrapper-->
                                <div class="stepper-wrapper d-flex align-items-center">
                                    <!--begin::Icon-->
                                    <div class="stepper-icon w-40px h-40px">
                                        <i class="stepper-check fas fa-check"></i>
                                        <span class="stepper-number">2</span>
                                    </div>
                                    <!--begin::Icon-->
                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <div class="stepper-desc">
                                            Event
                                        </div>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Wrapper-->

                                <!--begin::Line-->
                                <div class="stepper-line h-40px"></div>
                                <!--end::Line-->
                            </div>
                            <!--end::Step 2-->

                            <!--begin::Step 3-->
                            <div class="stepper-item {{$stepperIndex == 3?'current': ($stepperIndex > 3?'completed': '')}}" data-kt-stepper-element="nav">
                                <!--begin::Wrapper-->
                                <div class="stepper-wrapper d-flex align-items-center">
                                    <!--begin::Icon-->
                                    <div class="stepper-icon w-40px h-40px">
                                        <i class="stepper-check fas fa-check"></i>
                                        <span class="stepper-number">3</span>
                                    </div>
                                    <!--begin::Icon-->
                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <div class="stepper-desc">
                                            People
                                        </div>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Wrapper-->

                                <!--begin::Line-->
                                <div class="stepper-line h-40px"></div>
                                <!--end::Line-->
                            </div>
                            <!--end::Step 3-->

                            <!--begin::Step 4-->
                            <div class="stepper-item {{$stepperIndex == 4?'current': ($stepperIndex > 4?'completed': '')}}" data-kt-stepper-element="nav">
                                <!--begin::Wrapper-->
                                <div class="stepper-wrapper d-flex align-items-center">
                                    <!--begin::Icon-->
                                    <div class="stepper-icon w-40px h-40px">
                                        <i class="stepper-check fas fa-check"></i>
                                        <span class="stepper-number">4</span>
                                    </div>
                                    <!--begin::Icon-->
                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <div class="stepper-desc">
                                            Date and Buffer
                                        </div>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Wrapper-->

                                <!--begin::Line-->
                                <div class="stepper-line h-40px"></div>
                                <!--end::Line-->
                            </div>
                            <!--end::Step 4-->
                            <!--begin::Step 5-->
                            <div class="stepper-item {{$stepperIndex == 5?'current': ($stepperIndex > 5?'completed': '')}}" data-kt-stepper-element="nav">
                                <!--begin::Wrapper-->
                                <div class="stepper-wrapper d-flex align-items-center">
                                    <!--begin::Icon-->
                                    <div class="stepper-icon w-40px h-40px">
                                        <i class="stepper-check fas fa-check"></i>
                                        <span class="stepper-number">5</span>
                                    </div>
                                    <!--begin::Icon-->
                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <div class="stepper-desc">
                                        Venue and Area
                                        </div>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Wrapper-->

                                <!--begin::Line-->
                                <div class="stepper-line h-40px"></div>
                                <!--end::Line-->
                            </div>
                            <!--end::Step 5-->
                            
                            <div class="stepper-item {{$stepperIndex == 6?'current': ($stepperIndex > 6?'completed': '')}} {{sizeof($options) == 0?'d-none': ''}}" data-kt-stepper-element="nav">
                                <!--begin::Wrapper-->
                                <div class="stepper-wrapper d-flex align-items-center">
                                    <!--begin::Icon-->
                                    <div class="stepper-icon w-40px h-40px">
                                        <i class="stepper-check fas fa-check"></i>
                                        <span class="stepper-number">6</span>
                                    </div>
                                    <!--begin::Icon-->
                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <div class="stepper-desc">
                                            Options
                                        </div>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Wrapper-->

                                <!--begin::Line-->
                                <div class="stepper-line h-40px"></div>
                                <!--end::Line-->
                            </div>

                            <div class="stepper-item {{$stepperIndex == 7?'current': ($stepperIndex > 7?'completed': '')}}" data-kt-stepper-element="nav">


                                <!--begin::Wrapper-->
                                <div class="stepper-wrapper d-flex align-items-center">
                                    <!--begin::Icon-->
                                    <div class="stepper-icon w-40px h-40px">
                                        <i class="stepper-check fas fa-check"></i>
                                        <span class="stepper-number">{{sizeof($options) == 0?'6': '7'}}</span>
                                    </div>
                                    <!--begin::Icon-->
                                    <!--begin::Label-->
                                    <div class="stepper-label">
                                        <div class="stepper-desc">
                                            Review
                                        </div>
                                    </div>
                                    <!--end::Label-->
                                </div>
                                <!--end::Wrapper-->
                            </div>
                        </div>
                    </div>
                    <!--begin::Form-->
                    <form class="form w-100" novalidate="novalidate" wire:submit.prevent="submit">
                        <!--begin::Group-->
                        <div class="w-100 d-flex justify-content-center px-4 mb-10" style="min-height: 50vh">
                            <!--begin::Step 1-->
                            <div class="flex-column w-100 {{$stepperIndex == 1?'current': ($stepperIndex > 1?'completed': '')}}" data-kt-stepper-element="content">
                                <div class="fv-row mb-10">
                                    <label for="contactSelect" class="form-label">Select Contact:</label>
                                    <select class="form-select form-select-solid" id="contactSelect" wire:model="contact_id">
                                        <option value="">Select a contact</option>
                                        @foreach ($filteredContacts as $contact)
                                            <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="accordion accordion-icon-collapse" id="kt_accordion_3">
                                    <div class="mb-5">
                                        <div class="accordion-header py-3 d-flex collapsed" data-bs-toggle="collapse" data-bs-target="#kt_accordion_item">
                                            <span class="accordion-icon">
                                                <i class="ki-duotone ki-plus-square fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                <i class="ki-duotone ki-minus-square fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
                                            </span>
                                            <h3 class="fs-4 fw-semibold mb-0 ms-4">Add Contact</h3>
                                        </div>
                                        <div id="kt_accordion_item" class="fs-6 collapse pt-10" data-bs-parent="#kt_accordion_3">
                                            <livewire:quote.add-contact-form></livewire:quotep.add-contact-form>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <!--begin::Step 1-->

                            <!--begin::Step 2-->
                            <div class="flex-column w-100 {{$stepperIndex == 2?'current': ($stepperIndex > 2?'completed': '')}}" data-kt-stepper-element="content">
                                <!--begin::Input group-->
                                <div class="fv-row mb-10">
                                    <label for="eventNameSelect" class="form-label">Select Event:</label>
                                    <select class="form-select form-select-solid" id="eventNameSelect" wire:model="event_name">
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
                                    <select class="form-select form-select-solid" id="eventSelect" wire:model="event_type">
                                        <option value="">Select an event</option>
                                        @foreach ($filteredEventTypes as $event)
                                            <option value="{{ $event->id }}">{{ $event->event_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--begin::Step 2-->

                            <!--begin::Step 3-->
                            <div class="flex-column w-100 {{$stepperIndex == 3?'current': ($stepperIndex > 3?'completed': '')}}" data-kt-stepper-element="content">
                                <!--begin::Input group-->
                                <div class="fv-row mb-10">
                                    <label for="people" class="form-label">How many people will attend</label>
                                    <input type="number" class="form-control form-control-solid mb-3 mb-lg-0"
                                        wire:model.defer="people" 
                                        placeholder="Number of people" id="people"
                                        min="{{$selectedEvent? $selectedEvent->min_people: 0}}"
                                        max="{{$selectedEvent? $selectedEvent->max_people: 0}}"
                                    />

                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--begin::Step 3-->

                            <!--begin::Step 4-->
                            <div class="flex-column w-100 {{$stepperIndex == 4?'current': ($stepperIndex > 4?'completed': '')}}" data-kt-stepper-element="content">
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

                               <div class="px-20">
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

                                    <div class="">
                                        <label class="fw-semibold fs-6 mb-4 pb-5">@if(sizeof($time_ranges) !== 1){{ $date }}@endif</label>
                                        <div id="event_time_slider_{{ $loop->index }}" class="my-4"></div>
                                    </div>
                                @endforeach
                               </div>

                                <div class="fv-row my-10">
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
                                            <select class="form-select form-select-solid" id="buffer_time_unit" wire:model="buffer_time_unit">
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
                            </div>
                            <!--begin::Step 4-->

                            <!--begin::Step 5-->
                            <div class="flex-column w-100 {{$stepperIndex == 5?'current': ($stepperIndex > 5?'completed': '')}}" data-kt-stepper-element="content">
                                <!--begin::Input group-->
                                <div class="fv-row mb-10">
                                    <label for="selectedVenueId" class="form-label">Select Venue:</label>
                                    <select class="form-select form-select-solid" wire:model="selectedVenueId" id="selectedVenueId">
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
                                    <select class="form-select form-select-solid" id="areaSelect" wire:model="area_id">
                                        <option value="">Select an area</option>
                                        @foreach ($filteredAreas as $area)
                                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!--end::Input group-->
                            </div>
                            <!--begin::Step 5-->

                            <div class="flex-column w-100 {{$stepperIndex == 6?'current': ($stepperIndex > 6?'completed': '')}} {{sizeof($options) == 0? 'd-none': ''}}" data-kt-stepper-element="content">
                                <div id="option-content">
                                    <!-- Loop through each option and render based on kind -->
                                    @foreach ($options as $option)
                                        <div class="fv-row mb-10">
                                            <!-- For 'yes_no', show a select dropdown with Yes and No options -->
                                            @if($option->type === 'yes_no')
                                                <label for="option{{ $option->id }}" class="form-label">{{ $option->name }}:</label>
                                                <select class="form-select form-select-solid" wire:change="updateSelectedOption({{ $option->id }}, $event.target.value)">
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
                                                <select class="form-select form-select-solid" wire:change="updateSelectedOption({{ $option->id }}, $event.target.value)">
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
                                            @if($option->type === 'always')
                                                <input type="hidden" wire:change="updateSelectedOption({{ $option->id }}, $event.target.value)" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Number"/>
                                            @endif

                                            <!-- For 'logic', show number input -->
                                            @if($option->type === 'logic')
                                                <input type="hidden" wire:model="selectedOptions.{{ $option->id }}" class="form-control form-control-solid mb-3 mb-lg-0" value="{{ $option->value }}" />
                                            @endif

                                        </div>
                                    @endforeach
                                    <!-- <label class="form-label">Drinks:</label> -->
                                    <!-- <div class="form-check form-check-custom form-check-solid mb-5">
                                        <input wire:click="updateDrinkState('noDrink')" class="form-check-input" type="checkbox"/>
                                        <label class="form-check-label" for="flexCheckDefault">
                                            No Drinks
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid mb-5" id="soft-drinks-checkbox">
                                        <input wire:click="updateDrinkState('softDrink')" class="form-check-input" type="checkbox" @if(!$isDrink) disabled @endif/>
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Soft Drinks
                                        </label>
                                    </div>
                                    <div class="form-check form-check-custom form-check-solid mb-5" id="cocktails-checkbox">
                                        <input wire:click="updateDrinkState('cocktails')" class="form-check-input" type="checkbox" @if(!$isDrink) disabled @endif/>
                                        <label class="form-check-label" for="flexCheckDefault">
                                            Cocktails
                                        </label>
                                    </div> -->
                                </div>
                            </div>
                    

                            <div class="flex-column w-100 {{$stepperIndex == 7?'current': ($stepperIndex > 7?'completed': '')}}" data-kt-stepper-element="content">
                               <div class="mb-10 px-4 overflow-x-hidden">
                                    <!--begin::Input group-->
                                    <div class="form-floating mb-7">
                                        <input type="text" class="form-control form-control-solid" disabled value="{{$contact_id?collect($filteredContacts)->where('id', $contact_id)->first()->name: 'N/A'}}"/>
                                        <label>Contact:</label>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="form-floating mb-7">
                                        <input type="text" class="form-control form-control-solid" disabled value="{{$event_name??'N/A'}} - {{$event_type?collect($filteredEventTypes)->where('id', $event_type)->first()->name: 'N/A'}}"/>
                                        <label>Event:</label>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="form-floating mb-7">
                                        <input type="text" class="form-control form-control-solid" disabled value="{{$people??'N/A'}}"/>
                                        <label>People:</label>
                                    </div>
                                    <!--end::Input group-->

                                <div class="row">
                                        <div class="col">
                                            <!--begin::Input group-->
                                            <div class="form-floating mb-7">
                                                <input type="text" class="form-control form-control-solid" disabled value="{{$date_from??'N/A'}}"/>
                                                <label>Date From:</label>
                                            </div>
                                            <!--end::Input group-->
                                        </div>

                                        <div class="col">
                                            <!--begin::Input group-->
                                            <div class="form-floating mb-7">
                                                <input type="text" class="form-control form-control-solid" disabled value="{{$date_to??'N/A'}}"/>
                                                <label>Date To:</label>
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                </div>

                                <div class="row px-10">
                                    @foreach ($time_ranges as $date => $time_range)
                                        <div class="col-4">
                                            <!--begin::Input group-->
                                            <div class="form-floating mb-7">
                                                <input type="text" class="form-control form-control-solid" disabled value="{{$time_range['time_from']}} ~ {{$time_range['time_to']}}"/>
                                                <label>{{$date}}:</label>
                                            </div>
                                            <!--end::Input group-->
                                        </div>
                                    @endforeach
                                    </div>

                                <div class="row">
                                    <div class="col">
                                        <!--begin::Input group-->
                                        <div class="form-floating mb-7">
                                            <input type="text" class="form-control form-control-solid" disabled value="{{$buffer_time_before??'N/A'}} {{$buffer_time_unit}}"/>
                                            <label>Buffer Time Before the Event Start:</label>
                                        </div>
                                        <!--end::Input group-->
                                    </div>

                                    <div class="col">
                                        <!--begin::Input group-->
                                        <div class="form-floating mb-7">
                                            <input type="text" class="form-control form-control-solid" disabled value="{{$buffer_time_after??'N/A'}} {{$buffer_time_unit}}"/>
                                            <label>Buffer Time After the Event End:</label>
                                        </div>
                                        <!--end::Input group-->
                                    </div>
                                </div>

                                    <!--begin::Input group-->
                                    <div class="form-floating mb-7">
                                        <input type="text" class="form-control form-control-solid" disabled value="{{$selectedVenueId?collect($filteredVenues)->where('id', $selectedVenueId)->first()->name: 'N/A'}}"/>
                                        <label>Venue:</label>
                                    </div>
                                    <!--end::Input group-->

                                    <!--begin::Input group-->
                                    <div class="form-floating mb-7">
                                        <input type="text" class="form-control form-control-solid" disabled value="{{$area_id?collect($filteredAreas)->where('id', $area_id)->first()->name: 'N/A'}}"/>
                                        <label>Venue Area:</label>
                                    </div>
                                    <!--end::Input group-->

                                    @foreach($selectedOptions as $optionId => $optionValue)
                                        @if($optionValue)
                                            <!--begin::Input group-->
                                            <div class="form-floating mb-7">
                                                <input type="text" class="form-control form-control-solid" readonly value="{{$optionValue}}"/>
                                                <label>{{collect($options)->where('id', $optionId)->first()?collect($options)->where('id', $optionId)->first()->name: 'N/A'}}</label>
                                            </div>
                                            <!--end::Input group-->
                                        @endif
                                    @endforeach
                                </div>                                
                                <div class="fv-row mb-10">
                                    <label for="discountField" class="form-label">Discount:</label>
                                    <input type="text" wire:model.defer="discount" class="form-control form-control-solid" placeholder="Enter a number or a percentage (e.g., 20 or 15%)" id="discountField" />
                                    @error('discount')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                           
                        </div>
                        <!--end::Group-->

                        <div class="separator border-3 mb-4"></div>

                        <!--begin::Actions-->
                        <div class="d-flex flex-stack">
                            <!--begin::Wrapper-->
                            <div class="me-2">
                                <button type="button" class="btn btn-light btn-active-light-primary" style="display: {{$stepperIndex != 1?'block':'none'}}" data-kt-stepper-action="previous">
                                    Back
                                </button>
                            </div>
                            <!--end::Wrapper-->

                            <!--begin::Wrapper-->
                            <div>
                                <button type="submit" class="btn btn-primary" id="submit_button" data-kt-stepper-action="submit" @if(sizeof($time_ranges) === 0 || !$selectedEvent) disabled @endif>
                                    <span class="indicator-label" wire:loading.remove wire:target="submit">
                                        Submit
                                    </span>
                                    <span class="indicator-progress" wire:loading wire:target="submit">
                                        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                    </span>
                                </button>

                                <button type="button" class="btn btn-primary" data-kt-stepper-action="next">
                                    Continue
                                </button>
                            </div>
                            <!--end::Wrapper-->
                        </div>
                        <!--end::Actions-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Stepper-->
            </div>
        </div>
    </div>
</div>

@push('scripts')

<script>
    document.addEventListener('livewire:load', function () {
        Livewire.on('create_contact_success', function (msg) {
            toastr.success(msg);
            Livewire.emit('update_filtered_contact_list')
        });
    });
</script>

<script>

    // Stepper lement
    var element = document.querySelector("#kt_stepper");

    // Initialize Stepper
    var stepper = new KTStepper(element);

    // Handle next step
    stepper.on("kt.stepper.next", function (s) {
        
        if(s.currentStepIndex === 5 && document.getElementById('option-content').innerText.trim() == '') Livewire.emit('set_stepper_index', s.currentStepIndex + 2)
        else Livewire.emit('set_stepper_index', s.currentStepIndex + 1)
    });

    // Handle previous step
    stepper.on("kt.stepper.previous", function (s) {
        if(s.currentStepIndex === 7 && document.getElementById('option-content').innerText.trim() == '') Livewire.emit('set_stepper_index', s.currentStepIndex - 2)
        else Livewire.emit('set_stepper_index', s.currentStepIndex - 1)
    });

    document.addEventListener('livewire:load', function () {
        window.addEventListener('stepper-index-updated', event => {
            stepper.goTo(event.detail.index);
        })
    });

</script>

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
                // const maxDuration = Number(event.detail.selectedEvent?event.detail.selectedEvent["max_duration"]: 24);
                // const minDuration = Number(event.detail.selectedEvent?event.detail.selectedEvent["min_duration"]: 24);
                // console.log(maxDuration, minDuration)
                // const duration = convertTimeToDecimal(values[1]) - convertTimeToDecimal(values[0]);
                // if(handle) {
                //     if(duration < minDuration) values[1] = convertDecimalToTime(convertTimeToDecimal(values[0]) + minDuration);
                //     if(duration > maxDuration) values[1] = convertDecimalToTime(convertTimeToDecimal(values[0]) + maxDuration);
                // } else {
                //     if(duration < minDuration) values[0] = convertDecimalToTime(Math.max(convertTimeToDecimal(values[1]) - minDuration, 0));
                //     if(duration > maxDuration) values[0] = convertDecimalToTime(Math.max(convertTimeToDecimal(values[1]) - maxDuration, 0));
                // }
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
        useCurrent: true,
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