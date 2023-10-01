<div class="modal fade" id="kt_modal_add_option" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_option_header">
                <h2 class="fw-bold">Add Option</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body flex-center  px-5 my-7">
                <form id="kt_modal_add_option_form" class="form" wire:submit.prevent="submit">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_option_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_option_header" data-kt-scroll-wrappers="#kt_modal_add_option_scroll" data-kt-scroll-offset="300px">
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <div class="col">
                            <!-- Name -->
                            <label class="required fw-semibold fs-6 mb-2">Name</label>
                            <input type="text" wire:model.defer="name" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Name"/>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>
                            <div class="col">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">Position</label>
                            <input type="number" wire:model.defer="position" name="position" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Position"/>
                            @error('position')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                            </div>
                        </div>
                        <!--end::Input group-->
                        
                        <!-- Type -->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Type</label>
                            <select wire:model.defer="type" name="type" class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="">Select</option>
                                <option value="yes_no">Yes/No</option>
                                <option value="check">Check</option>
                                <option value="radio">Radio</option>
                            </select>
                            @error('type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <!-- Values -->
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Values (Separated by '|')</label>
                            <textarea wire:model.defer="values" name="values" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Value1|Value2|Value3"></textarea>
                            @error('values')
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
