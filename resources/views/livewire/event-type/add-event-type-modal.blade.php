<div class="modal fade" id="kt_modal_add_event_type" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_event_type_header">
                <h2 class="fw-bold">Add Event Type</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    <!-- Add your close icon here -->
                </div>
            </div>
            <div class="modal-body px-5 my-7">
                <form id="kt_modal_add_event_type_form" class="form" wire:submit.prevent="submit">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_event_type_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_event_type_header" data-kt-scroll-wrappers="#kt_modal_add_event_type_scroll" data-kt-scroll-offset="300px">
                        
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Name (Event Type)</label>
                            <select wire:model.defer="name" name="name" class="form-select form-select-solid">
                                    <option value="">Select</option>
                                    <option value="wedding">Wedding</option>
                                    <option value="birthday">Birthday Party</option>
                                    <option value="summer">Summer Party</option>
                                    <option value="corporate">Corporate Event</option>
                                    <!-- Add more options as needed -->
                                </select>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row mb-7">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Typical Seating</label>
                                <!-- Add a select field for seating (you can use Livewire select2 component or native select) -->
                                <select wire:model.defer="typical_seating" name="typical_seating" class="form-select form-select-solid">
                                    <option value="">Select</option>
                                    <option value="noseating">No Seating</option>
                                    <option value="seatingrows">In Rows</option>
                                    <option value="seatingtables">Tables</option>
                                    <!-- Add more options as needed -->
                                </select>
                                @error('typical_seating')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Duration Type</label>
                                <!-- Add a select field for duration type (you can use Livewire select2 component or native select) -->
                                <select wire:model.defer="duration_type" name="duration_type" class="form-select form-select-solid">
                                    <option value="">Select</option>
                                    <option value="days">Days</option>
                                    <option value="hours">Hours</option>
                                    <option value="minutes">Minutes</option>
                                    <!-- Add more options as needed -->
                                </select>
                                @error('duration_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-7">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Duration</label>
                                <input type="number" wire:model.defer="duration" name="duration" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Duration"/>
                                @error('duration')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Min Duration</label>
                                <input type="number" wire:model.defer="min_duration" name="min_duration" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Min Duration"/>
                                @error('min_duration')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-7">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Buffer Time (Before)</label>
                                <input type="number" wire:model.defer="time_setup" name="time_setup" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Time before the event"/>
                                @error('time_setup')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Buffer Time (After)</label>
                                <input type="number" wire:model.defer="time_cleaningup" name="time_cleaningup" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Time after the event"/>
                                @error('time_cleaningup')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="row mb-7">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Availability Season</label>
                                <!-- Add a select field for the season (you can use Livewire select2 component or native select) -->
                                <select wire:model.defer="season_id" name="season_id" class="form-select form-select-solid">
                                    <option value="">Select</option>
                                    <option value="0">All</option>
                                    @foreach($seasons as $season)
                                        <option value="{{ $season->id }}">{{ $season->name }}</option>
                                    @endforeach
                                </select>
                                @error('season_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Availability Days</label>
                                <!-- Add a select field for the season (you can use Livewire select2 component or native select) -->
                                <select wire:model.defer="availability" name="availability" class="form-select form-select-solid">
                                    <option value="">Select</option>
                                    <option value="0">All</option>
                                        <option value="Monday">Monday</option>
                                        <option value="Tuesday">Tuesday</option>
                                        <option value="Wednesday">Wednesday</option>
                                        <option value="Thursday">Thursday</option>
                                        <option value="Friday">Friday</option>
                                        <option value="Saturday">Saturday</option>
                                        <option value="Sunday">Sunday</option>
                                </select>
                                @error('availability')
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
