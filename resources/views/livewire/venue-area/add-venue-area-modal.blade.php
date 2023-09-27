<div class="modal fade" id="kt_modal_add_venue_area" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_venue_area_header">
                <h2 class="fw-bold">Add Venue Area</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body px-5 my-7">
                <form id="kt_modal_add_venue_area_form" class="form" wire:submit.prevent="submit">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_venue_area_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_venue_area_header" data-kt-scroll-wrappers="#kt_modal_add_venue_area_scroll" data-kt-scroll-offset="300px">
                        
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Name</label>
                            <input type="text" wire:model.defer="name" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Name"/>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Capacity</label>
                            <div class="col">
                            <input type="number" wire:model.defer="capacity_noseating" name="capacity_noseating" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Standing Capacity"/>
                            @error('capacity_noseating')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>
                            <div class="col">
                            <input type="number" wire:model.defer="capacity_seatingrows" name="capacity_seatingrows" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="In Rows Capacity"/>
                            @error('capacity_seatingrows')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>
                            <div class="col">
                            <input type="number" wire:model.defer="capacity_seatingtables" name="capacity_seatingtables" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="In Tables Capacity"/>
                            @error('capacity_seatingtables')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Venue</label>
                            <select wire:model.defer="venue_id" name="venue_id" class="form-select form-select-solid">
                                <option value="">Select Venue</option>
                                <!-- Populate this with available Venues -->
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                                @endforeach
                            </select>
                            @error('venue_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
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
