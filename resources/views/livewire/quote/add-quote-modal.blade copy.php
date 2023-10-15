<div class="modal fade" id="kt_modal_add_quote" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_quote_header">
                <h2 class="fw-bold">Add Quote</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body flex-center  px-5 my-7">
                <form id="kt_modal_add_quote_form" class="form" wire:submit.prevent="submit">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_quote_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_quote_header" data-kt-scroll-wrappers="#kt_modal_add_quote_scroll" data-kt-scroll-offset="300px">
                        
                        <!--begin::Step 1-->
                                <!-- Contact Selection -->
                                <div class="mb-3">
                                    <label for="contactSelect" class="form-label">Select Contact:</label>
                                    <select class="form-select" id="contactSelect" wire:model="contact_id">
                                        <option value="">Select a contact</option>
                                        @foreach ($contacts as $contact)
                                            <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <!-- Event Selection -->
                                <div class="mb-3">
                                    <label for="eventSelect" class="form-label">Select Event:</label>
                                    <select class="form-select" id="eventSelect" wire:model="event_type">
                                        <option value="">Select an event</option>
                                        @foreach ($eventTypes as $event)
                                            <option value="{{ $event->id }}">{{ $event->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            <!--begin::Step 1-->
                            <!-- Venue Selection -->
                                <div class="mb-3">
                                    <label for="venueSelect" class="form-label">Select Venue:</label>
                                    <select class="form-select" id="venueSelect" >
                                        <option value="">Select a venue</option>
                                        @foreach ($venues as $venue)
                                            <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Area Selection -->
                                <div class="mb-3">
                                    <label for="areaSelect" class="form-label">Select Areas:</label>
                                    <select class="form-select" id="areaSelect" wire:model="area_id">
                                        <option value="">Select an area</option>
                                        @foreach ($venueAreas as $area)
                                            <option value="{{ $area->id }}">{{ $area->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="row mb-7">
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

                                <div class="row mb-7">
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
                </form>
            </div>
        </div>
    </div>
</div>
