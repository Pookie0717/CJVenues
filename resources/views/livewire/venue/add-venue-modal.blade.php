<div class="modal fade" id="kt_modal_add_venue" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_venue_header">
                <h2 class="fw-bold">Add Venue</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body px-5 my-7">
                <form id="kt_modal_add_venue_form" class="form" wire:submit.prevent="submit">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_venue_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_venue_header" data-kt-scroll-wrappers="#kt_modal_add_venue_scroll" data-kt-scroll-offset="300px">
                        
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Name</label>
                            <input type="text" wire:model.defer="name" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Name"/>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Type</label>
                            <input type="text" wire:model.defer="type" name="type" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Type"/>
                            @error('type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Address</label>
                            <input type="text" wire:model.defer="address" name="address" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Address"/>
                            @error('address')
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