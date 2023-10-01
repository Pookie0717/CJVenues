<div class="modal fade" id="kt_modal_add_contact" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_contact_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">Add Contact</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body px-5 my-7">
                <!--begin::Form-->
                <form id="kt_modal_add_contact_form" class="form" action="#" wire:submit.prevent="submit" enctype="multipart/form-data">
                    <!--begin::Scroll-->
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_contact_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_contact_header" data-kt-scroll-wrappers="#kt_modal_add_contact_scroll" data-kt-scroll-offset="300px">
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <div class="col">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">First Name</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" wire:model.defer="first_name" name="first_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="First name"/>
                            <!--end::Input-->
                            @error('first_name')
                            <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">Last Name</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" wire:model.defer="last_name" name="last_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Last name"/>
                            <!--end::Input-->
                            @error('last_name')
                            <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">Email</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="email" wire:model.defer="email" name="email" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="example@example.com"/>
                            <!--end::Input-->
                            @error('email')
                            <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">Phone</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="phone" wire:model.defer="phone" name="phone" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="0423 3043 302"/>
                            <!--end::Input-->
                            @error('phone')
                            <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="fv-row mb-7">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">Address</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" wire:model.defer="address" name="address" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Address"/>
                            <!--end::address-->
                            @error('address')
                            <span class="text-danger">{{ $message }}</span> @enderror
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <div class="col">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">City</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" wire:model.defer="city" name="city" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="City"/>
                            <!--end::address-->
                            @error('city')
                            <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">Postcode</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" wire:model.defer="postcode" name="postcode" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Postcode"/>
                            <!--end::address-->
                            @error('postcode')
                            <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <div class="col">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">State</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" wire:model.defer="state" name="state" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="State"/>
                            <!--end::address-->
                            @error('state')
                            <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="col">
                            <!--begin::Label-->
                            <label class="required fw-semibold fs-6 mb-2">Country</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" wire:model.defer="country" name="country" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Country"/>
                            <!--end::address-->
                            @error('country')
                            <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <!--end::Input group-->
                        <!--begin::Input group-->
                        <div class="mb-7">
                            <!--begin::Label-->
                            <label class="fw-semibold fs-6 mb-5">Notes</label>
                            <textarea class="form-control form-control-solid mb-3 mb-lg-0" id="notes" rows="3" wire:model.defer="notes" placeholder="Enter notes"></textarea>
                            <!--end::Label-->
                            @error('notes')
                            <span class="text-danger">{{ $message }}</span> @enderror
                            
                        </div>
                        <!--end::Input group-->
                    </div>
                    <!--end::Scroll-->
                    <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">Discard</button>
                        <button type="submit" class="btn btn-primary" data-kt-contacts-modal-action="submit">
                            <span class="indicator-label" wire:loading.remove>Submit</span>
                            <span class="indicator-progress" wire:loading wire:target="submit">
                                Please wait...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                    <!--end::Actions-->
                </form>
                <!--end::Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>
