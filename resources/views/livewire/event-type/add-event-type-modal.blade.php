<div class="modal fade" id="kt_modal_add_event_type" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_event_type_header">
                <h2 class="fw-bold">Add Event Package</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    <!-- Add your close icon here -->
                </div>
            </div>
            <div class="modal-body px-5 my-7">
                <form id="kt_modal_add_event_type_form" class="form" wire:submit.prevent="submit">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_event_type_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_event_type_header" data-kt-scroll-wrappers="#kt_modal_add_event_type_scroll" data-kt-scroll-offset="300px">
                        
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Package Name</label>
                            <input type="text" wire:model.defer="event_name" name="event_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Package Name"/>
                            @error('event_name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row mb-7">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Description</label>
                                <input type="text" wire:model.defer="description" name="description" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Package Description"/>
                                @error('description')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Event Category</label>
                            <select wire:model.defer="selectedEventNames" name="name[]" class="form-select form-select-solid" multiple>
                                <option value="wedding">Wedding</option>
                                <option value="birthday">Birthday Party</option>
                                <option value="summer">Summer Party</option>
                                <option value="corporate">Corporate Event</option>
                                <!-- Add more options as needed -->
                            </select>
                            @error('selectedEventNames')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row mb-7">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Typical Seating</label>
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
                                <select wire:model.defer="duration_type" name="duration_type" class="form-select form-select-solid">
                                    <option value="">Select</option>
                                    <option value="days">Days</option>
                                    <option value="hours">Hours</option>
                                    <!-- Add more options as needed -->
                                </select>
                                @error('duration_type')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-7">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Min Duration</label>
                                <input type="number" wire:model.defer="min_duration" name="min_duration" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Min Duration"/>
                                @error('min_duration')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Max Duration</label>
                                <input type="number" wire:model.defer="max_duration" name="max_duration" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Max Duration"/>
                                @error('max_duration')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-7">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Min People</label>
                                <input type="number" wire:model.defer="min_people" name="min_people" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Min People"/>
                                @error('min_people')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Max People</label>
                                <input type="number" wire:model.defer="max_people" name="max_people" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Max People"/>
                                @error('max_people')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-7">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Min Buffer Before</label>
                                <input type="number" wire:model.defer="min_buffer_before" name="min_buffer_before" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Minimum Buffer Before"/>
                                @error('min_buffer_before')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Max Buffer Before</label>
                                <input type="number" wire:model.defer="max_buffer_before" name="max_buffer_before" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Maximum Buffer Before"/>
                                @error('max_buffer_before')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-7">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Min Buffer After</label>
                                <input type="number" wire:model.defer="min_buffer_after" name="min_buffer_after" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Minimum Buffer After"/>
                                @error('min_buffer_after')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Max Buffer After</label>
                                <input type="number" wire:model.defer="max_buffer_after" name="max_buffer_after" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Maximum Buffer After"/>
                                @error('max_buffer_after')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Venue Area</label>
                            <select wire:model.defer="venue_area_id" name="venue_area_id" class="form-select form-select-solid">
                                <option value="">Select</option>
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
                        <div class="row">
                                    <div class="col">
                                        <label class="required fw-semibold fs-6 mb-2">Opening Time</label>
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
                                        <label class="required fw-semibold fs-6 mb-2">Closing Time</label>
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
                    </div>
                    <!--end::Input group-->

                        <div class="row mb-7">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Season</label>
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

    @push('scripts')
<script>   

document.getElementById('opening_time_picker_input').addEventListener('change', function () {
    @this.set('opening_time', this.value);
});

document.getElementById('closing_time_picker_input').addEventListener('change', function () {
    @this.set('closing_time', this.value);
});

</script>
    @endpush
