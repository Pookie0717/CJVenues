<div class="modal fade" id="kt_modal_add_venue" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_venue_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">{{ trans('venues.addvenue') }}</h2>
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
        <form id="kt_modal_add_venue_form" class="form" wire:submit.prevent="submit" enctype="multipart/form-data">
            <!-- ... Other fields ... -->
            <!--begin::Input group for Venue Name -->
            <div class="fv-row mb-7">
                <!--begin::Label-->
                <label class="required fw-semibold fs-6 mb-2">{{ trans('venues.venuename') }}</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" wire:model.defer="venueName" name="venueName" class="form-control form-control-solid mb-3" placeholder="{{ trans('venues.venuename') }}"/>
                <!--end::Input-->
                @error('venueName')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <!--end::Input group-->

            <!--begin::Input group for Venue Type -->
            <div class="fv-row mb-7">
                <!--begin::Label-->
                <label class="required fw-semibold fs-6 mb-2">{{ trans('venues.venuetype') }}</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" wire:model.defer="venueType" name="venueType" class="form-control form-control-solid mb-3" placeholder="{{ trans('venues.venuetype') }}"/>
                <!--end::Input-->
                @error('venueType')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <!--end::Input group-->

            <!--begin::Input group for Venue Address -->
            <div class="fv-row mb-7">
                <!--begin::Label-->
                <label class="required fw-semibold fs-6 mb-2">{{ trans('venues.venueaddress') }}</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" wire:model.defer="venueAddress" name="venueAddress" class="form-control form-control-solid mb-3" placeholder="{{ trans('venues.venueaddress') }}"/>
                <!--end::Input-->
                @error('venueAddress')
                <span class="text-danger">{{ $message }}</span>
                @enderror
            </div>
            <!--end::Input group-->

            <!--begin::Input group for Venue Postcode -->
            <div class="row">
                <div class="col">
                    <!--begin::Label-->
                    <label class="required fw-semibold fs-6 mb-2">{{ trans('fields.postcode') }}</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="text" wire:model.defer="postcode" name="postcode" class="form-control form-control-solid mb-7" placeholder="{{ trans('fields.postcode') }}"/>
                    <!--end::Input-->
                    @error('postcode')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                <!--end::Input group-->
                </div>
                <div class="col">
                <!--begin::Input group for Venue City -->
                    <!--begin::Label-->
                    <label class="required fw-semibold fs-6 mb-2">{{ trans('fields.city') }}</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="text" wire:model.defer="city" name="city" class="form-control form-control-solid mb-7" placeholder="{{ trans('fields.city') }}"/>
                    <!--end::Input-->
                    @error('city')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
                 <div class="col">
                    <!--begin::Label-->
                    <label class="required fw-semibold fs-6 mb-2">{{ trans('fields.state') }}</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input type="text" wire:model.defer="state" name="state" class="form-control form-control-solid mb-7" placeholder="{{ trans('fields.state') }}"/>
                    <!--end::Input-->
                    @error('state')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>
            <!--end::Input group-->
            </div>

           <!-- Dynamic Area Input Fields -->
            @foreach ($areas as $index => $area)
            <div class="fv-row mb-7" wire:key="area-{{ $index }}">
                    <div class="col">
                        <!-- Area Name Input -->
                        <label class="required fw-semibold fs-6 mb-2">{{ trans('venues.areaname') }}</label>
                        <input type="text" wire:model="areas.{{ $index }}.name" class="form-control form-control-solid mb-3" placeholder="{{ trans('venues.areaname') }}"/>
                        @error("areas.{$index}.name")
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                <div class="row">
                    <div class="col">
                        <!-- Area Capacity Input (No Seating) -->
                        <label class="required fw-semibold fs-6 mb-2">{{ trans('venues.capacity_noseating') }}</label>
                        <input type="text" wire:model="areas.{{ $index }}.capacity_noseating" class="form-control form-control-solid mb-3" placeholder="{{ trans('venues.capacity_noseating') }}"/>
                        @error("areas.{$index}.capacity_noseating")
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col">
                        <!-- Area Capacity Input (Seating Rows) -->
                        <label class="required fw-semibold fs-6 mb-2">{{ trans('venues.capacity_seatingrows') }}</label>
                        <input type="text" wire:model="areas.{{ $index }}.capacity_seatingrows" class="form-control form-control-solid mb-3" placeholder="{{ trans('venues.capacity_seatingrows') }}"/>
                        @error("areas.{$index}.capacity_seatingrows")
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="col">
                        <!-- Area Capacity Input (Seating Tables) -->
                        <label class="required fw-semibold fs-6 mb-2">{{ trans('venues.capacity_seatingtables') }}</label>
                        <input type="text" wire:model="areas.{{ $index }}.capacity_seatingtables" class="form-control form-control-solid mb-3" placeholder="{{ trans('venues.capacity_seatingtables') }}"/>
                        @error("areas.{$index}.capacity_seatingtables")
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                </div>
                <div class="text-end">
                    <button type="button" wire:click="removeArea({{ $index }})" class="btn btn-danger btn-sm">{{ trans('venues.removearea') }}</button>
                </div>
            </div>
            @endforeach


            <!-- Add Area Button -->
            <div class="text-end">
                <button type="button" wire:click="addArea" class="btn btn-primary btn-sm">{{ trans('venues.addarea') }}</button>
            </div>
            <!-- ... Add Area button ... -->
            <!--begin::Actions-->
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">{{ trans('general.discard') }}</button>
                        <button type="submit" class="btn btn-primary" data-kt-contacts-modal-action="submit">
                            <span class="indicator-label" wire:loading.remove wire:target="submit">{{ trans('general.submit') }}</span>
                            <span class="indicator-progress" wire:loading wire:target="submit">
                                {{ trans('general.pleasewait') }}...
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