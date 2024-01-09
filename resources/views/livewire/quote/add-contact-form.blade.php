<div wire:ignore.self>
        <div class="d-flex flex-column px-10 px-lg-20">
            <!--begin::Input group-->
            <div class="row mb-4">
                <div class="col">
                <!--begin::Label-->
                <label class="required fw-semibold fs-6 mb-2">{{ trans('fields.firstname') }}</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" wire:model.defer="first_name" name="first_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ trans('fields.firstname') }}"/>
                <!--end::Input-->
                @error('first_name')
                <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <div class="col">
                <!--begin::Label-->
                <label class="required fw-semibold fs-6 mb-2">{{ trans('fields.lastname') }}</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" wire:model.defer="last_name" name="last_name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{ trans('fields.lastname') }}"/>
                <!--end::Input-->
                @error('last_name')
                <span class="text-danger">{{ $message }}</span> @enderror
                </div>
            </div>
            <!--end::Input group-->

            <div class="row">
                 <!--begin::Input group-->
                <div class="col-6 fv-row mb-4">
                    <!--begin::Label-->
                    <label class="required fw-semibold fs-6 mb-2">{{ trans('fields.email') }}</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="email" wire:model.defer="email" name="email" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="example@example.com"/>
                    <!--end::Input-->
                    @error('email')
                    <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="col-6 fv-row mb-4">
                    <!--begin::Label-->
                    <label class="required fw-semibold fs-6 mb-2">{{ trans('fields.phone') }}</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="phone" wire:model.defer="phone" name="phone" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="0423 3043 302"/>
                    <!--end::Input-->
                    @error('phone')
                    <span class="text-danger">{{ $message }}</span> @enderror
                </div>
                <!--end::Input group-->
            </div>

            <!--begin::Input groups for address details-->
            <div class="row">
                <!--begin::Input group for address-->
                <div class="col-md-6 fv-row mb-4">
                    <label class="fs-6 fw-semibold form-label mb-2">
                        <span>{{ trans('fields.address') }}</span>
                    </label>
                    <input class="form-control form-control-solid" placeholder="{{ trans('fields.enteraddress') }}" name="address" wire:model.defer="address"/>
                    @error('address')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!--begin::Input group for city-->
                <div class="col-md-6 fv-row mb-4">
                    <label class="fs-6 fw-semibold form-label mb-2">
                        <span>{{ trans('fields.city') }}</span>
                    </label>
                    <input class="form-control form-control-solid" placeholder="{{ trans('fields.entercity') }}" name="city" wire:model.defer="city"/>
                    @error('city')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            </div>

            <!--begin::Input groups for location details-->
            <div class="row">

                <!--begin::Input group for country-->
                <div class="col-md-4 fv-row mb-4">
                    <label class="fs-6 fw-semibold form-label mb-2">
                        <span>{{ trans('fields.country') }}</span>
                    </label>
                    <select class="form-select form-select-solid" name="country" wire:model="country">
                        <option>{{ trans('fields.selectacountry') }}</option>
                        @foreach ($countries as $code => $name)
                            <option value="{{ $code }}">{{ $name }}</option>
                        @endforeach
                    </select>
                    @error('country')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!--begin::Input group for state-->
                <div class="col-md-4 fv-row mb-4">
                    <label class="fs-6 fw-semibold form-label mb-2">
                        <span>{{ trans('fields.stateprovince') }}</span>
                    </label>
                    <select class="form-select form-select-solid" name="state" wire:model="state">
                        <option>{{ trans('fields.selectastateprovince') }}</option>
                        @foreach ($states as $e)
                            <option value="{{ $e }}">{{ $e }}</option>
                        @endforeach
                    </select>
                    @error('state')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!--begin::Input group for postcode-->
                <div class="col-md-4 fv-row mb-4">
                    <label class="fs-6 fw-semibold form-label mb-2">
                        <span>{{ trans('fields.postcode') }}</span>
                    </label>
                    <input class="form-control form-control-solid" placeholder="{{ trans('fields.enterpostcode') }}" name="postcode" wire:model.defer="postcode"/>
                    @error('postcode')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                
            </div>
            <!--begin::Input group-->
            <div class="mb-5">
                <!--begin::Label-->
                <label class="fw-semibold fs-6 mb-2">{{ trans('fields.notes') }}</label>
                <textarea class="form-control form-control-solid mb-3 mb-lg-0" id="notes" rows="3" wire:model.defer="notes" placeholder="{{ trans('fields.enternotes') }}"></textarea>
                <!--end::Label-->
                @error('notes')
                <span class="text-danger">{{ $message }}</span> @enderror
                
            </div>
            <!--end::Input group-->
        </div>
        <div class="px-10 px-lg-20 mb-4">
            <button class="btn btn-secondary" id="contact_form_submit">
                <span class="indicator-label">Add contact</span>
            </button>
        </div>
</div>

@push('scripts')
<script>
    document.getElementById('contact_form_submit').addEventListener('click', function() {
        Livewire.emit('contact_form_submit')
    })
</script>
@endpush