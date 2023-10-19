<div class="modal fade" id="kt_modal_edit_quote" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_edit_quote_header">
                <h2 class="fw-bold">Edit Quote</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body flex-center  px-5 my-7">
                <form class="form" novalidate="novalidate" id="kt_modal_edit_quote_form" class="form" wire:submit.prevent="submit">
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
                                    <select class="form-select" id="venueSelect" wire:model="selectedVenue">
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



                    </div>
                @endforeach
                <!--end::Input group-->
            </div>
            <!--begin::Step 1-->
        </div>
        <!--end::Group-->

        <!--begin::Actions-->
        <div class="d-flex flex-stack justify-content-center ">

            <!--begin::Wrapper-->
<div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">Discard</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label" wire:loading.remove>Submit</span>
                            <span class="indicator-progress" wire:loading wire:target="submit">
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
<script>
        
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