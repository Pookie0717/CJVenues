<div class="modal fade" id="kt_modal_add_quote" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content" wire:ignore>
            <div class="modal-header" id="kt_modal_add_quote_header">
                <h2 class="fw-bold">Add Quote</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body flex-center  px-5 my-7" >


<!--begin::Stepper-->
<div class="stepper stepper-pills" id="kt_stepper_example_basic">
<!--begin::Nav-->
<div class="stepper-nav flex-center flex-wrap mb-10">
    <!--begin::Step 1-->
    <div class="stepper-item mx-8 my-4 current" data-kt-stepper-element="nav" data-kt-stepper-action="step">
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
                <h3 class="stepper-title">
                    Step 1
                </h3>

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
    <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav" data-kt-stepper-action="step">
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
                <h3 class="stepper-title">
                    Step 2
                </h3>

                <div class="stepper-desc">
                    Event and Location
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
    <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav" data-kt-stepper-action="step">
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
                <h3 class="stepper-title">
                    Step 3
                </h3>

                <div class="stepper-desc">
                    Date and Time
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

    <!--begin::Step 3-->
    <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav" data-kt-stepper-action="step">
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
                <h3 class="stepper-title">
                    Step 4
                </h3>

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
    <!--end::Step 3-->


</div>
<!--end::Nav-->

    <!--begin::Form-->
    <form class="form w-lg-500px mx-auto" novalidate="novalidate" id="kt_stepper_example_basic_form">
        <!--begin::Group-->
        <div class="mb-5">
            <!--begin::Step 1-->
            <div class="flex-column current" data-kt-stepper-element="content">
                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <label for="contactSelect" class="form-label">Select Contact:</label>
                                    <select class="form-select" id="contactSelect" wire:model="contact_id">
                                        <option value="">Select a contact</option>
                                        @foreach ($contacts as $contact)
                                            <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                                        @endforeach
                                    </select>
                </div>
                <!--end::Input group-->
            </div>
            <!--begin::Step 1-->

            <!--begin::Step 1-->
            <div class="flex-column" data-kt-stepper-element="content">
                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <label for="eventSelect" class="form-label">Select Event:</label>
                                    <select class="form-select" id="eventSelect" wire:model="event_type">
                                        <option value="">Select an event</option>
                                        @foreach ($eventTypes as $event)
                                            <option value="{{ $event->id }}">{{ $event->name }}</option>
                                        @endforeach
                                    </select>
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <label for="venueSelect" class="form-label">Select Venue:</label>
                                    <select class="form-select" id="venueSelect" >
                                        <option value="">Select a venue</option>
                                        @foreach ($venues as $venue)
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
                                        @foreach ($venueAreas as $area)
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

                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <div class="row">
                                <div class="col">
                                    <label class="required fw-semibold fs-6 mb-2">Time From</label>
                                    <div class="input-group" id="time_from_picker_basic" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                        <input id="time_from_picker_input" type="text"  wire:model.defer="time_from" class="form-control" data-td-target="#time_from_picker"/>
                                        <span class="input-group-text" data-td-target="#time_from_picker" data-td-toggle="datetimepicker">
                                            <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                        </span>
                                    </div>
                                    @error('time_from')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="col">
                                    <label class="required fw-semibold fs-6 mb-2">Time To</label>
                                    <div class="input-group" id="time_to_picker_basic" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                        <input id="time_to_picker_input" type="text"  wire:model.defer="time_to" class="form-control" data-td-target="#time_to_picker"/>
                                        <span class="input-group-text" data-td-target="#time_to_picker" data-td-toggle="datetimepicker">
                                            <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                        </span>
                                    </div>
                                    @error('time_to')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                </div>
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
                            <label class="form-label">{{ $option->name }}:</label>
                            @foreach(explode('|', $option->values) as $value)
                                <div>
                                    <input id="selectedOptions{{ $option->id }}_{{ $value }}" type="radio" wire:change="updateSelectedOption({{ $option->id }}, '{{ $value }}')">
                                    <label for="selectedOptions{{ $option->id }}_{{ $value }}">{{ $value }}</label>
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



                    </div>
                @endforeach
                <!--end::Input group-->
            </div>
            <!--begin::Step 1-->
        </div>
        <!--end::Group-->

        <!--begin::Actions-->
        <div class="d-flex flex-stack">
            <!--begin::Wrapper-->
            <div class="me-2">
                <button type="button" class="btn btn-light btn-active-light-primary" data-kt-stepper-action="previous">
                    Back
                </button>
            </div>
            <!--end::Wrapper-->

            <!--begin::Wrapper-->
            <div>
                <button type="button" class="btn btn-primary" wire:click="submit" data-kt-stepper-action="submit">
                    <span class="indicator-label" wire:loading.remove>
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

// Stepper lement
var element = document.querySelector("#kt_stepper_example_basic");

// Initialize Stepper
var stepper = new KTStepper(element);

// Handle next step
stepper.on("kt.stepper.next", function (stepper) {
    stepper.goNext(); // go next step
});

// Handle previous step
stepper.on("kt.stepper.previous", function (stepper) {
    stepper.goPrevious(); // go previous step
});

document.getElementById('date_from_picker_input').addEventListener('change', function () {
    @this.set('date_from', this.value);
});

document.getElementById('date_to_picker_input').addEventListener('change', function () {
    @this.set('date_to', this.value);
});

document.getElementById('time_from_picker_input').addEventListener('change', function () {
    @this.set('time_from', this.value);
});

document.getElementById('time_to_picker_input').addEventListener('change', function () {
    @this.set('time_to', this.value);
});

</script>
    @endpush
