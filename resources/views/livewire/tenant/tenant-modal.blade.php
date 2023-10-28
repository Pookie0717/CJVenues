<div class="modal fade" id="kt_modal_update_tenant" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">Update Organisation</h2>
                <!--end::Modal title-->
                <!--begin::Close-->
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
                <!--end::Close-->
            </div>
            <!--end::Modal header-->
            <!--begin::Modal body-->
            <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
                <!--begin::Form-->
                <form id="kt_modal_update_tenant_form" class="form" action="#" wire:submit.prevent="submit">
                    <!--begin::Input group for name-->
                    <div class="fv-row mb-7">
                        <label class="fs-6 fw-semibold form-label mb-2">
                            <span class="required">Organisation Name</span>
                            <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true" data-bs-content="Organization names are required to be unique.">
                                {!! getIcon('information','fs-7') !!}
                            </span>
                        </label>
                        <input class="form-control form-control-solid" placeholder="Enter an organization name" name="name" wire:model.defer="name"/>
                        @error('name')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!--begin::Input groups for address details-->
                    <div class="row">
                        <!--begin::Input group for address-->
                        <div class="col-md-6 fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mb-2">
                                <span>Address</span>
                            </label>
                            <input class="form-control form-control-solid" placeholder="Enter address" name="address" wire:model.defer="address"/>
                            @error('address')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!--begin::Input group for city-->
                        <div class="col-md-6 fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mb-2">
                                <span>City</span>
                            </label>
                            <input class="form-control form-control-solid" placeholder="Enter city" name="city" wire:model.defer="city"/>
                            @error('city')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!--begin::Input groups for location details-->
                    <div class="row">

                        <!--begin::Input group for country-->
                        <div class="col-md-4 fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mb-2">
                                <span>Country</span>
                            </label>
                            <select class="form-select form-select-solid" name="country" wire:model="selectedCountry" placeholder="Select a country">
                                <option>Select a country</option>
                                @foreach ($this->getCountriesProperty() as $code => $name)
                                    <option value="{{ $code }}" {{ $code == $this->selectedCountry ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('country')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        

                        <!--begin::Input group for stateprovince-->
                        <div class="col-md-4 fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mb-2">
                                <span>State/Province</span>
                            </label>
                            <select class="form-select form-select-solid" name="stateprovince" wire:model="selectedState">
                                @foreach ($states as $state)
                                    <option value="{{ $state }}">{{ $state }}</option>
                                @endforeach
                            </select>
                            @error('stateprovince')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!--begin::Input group for postcode-->
                        <div class="col-md-4 fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mb-2">
                                <span>Postcode</span>
                            </label>
                            <input class="form-control form-control-solid" placeholder="Enter postcode" name="postcode" wire:model.defer="postcode"/>
                            @error('postcode')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                    </div>

                    <!--begin::Input groups for financial details-->
                    <div class="row">
                        <!--begin::Input group for currency-->
                        <div class="col-md-6 fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mb-2">
                                <span>Currency</span>
                            </label>
                            <input class="form-control form-control-solid" placeholder="Enter currency" name="currency" wire:model.defer="currency"/>
                            @error('currency')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!--begin::Input group for vatnumber-->
                        <div class="col-md-6 fv-row mb-7">
                            <label class="fs-6 fw-semibold form-label mb-2">
                                <span>VAT Number</span>
                            </label>
                            <input class="form-control form-control-solid" placeholder="Enter VAT number" name="vatnumber" wire:model.defer="vatnumber"/>
                            @error('vatnumber')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <!--begin::Actions-->
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