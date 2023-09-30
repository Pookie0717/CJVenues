<div class="modal fade" id="kt_modal_add_price" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_price_header">
                <h2 class="fw-bold">Add Price</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body flex-center  px-5 my-7">
                <form id="kt_modal_add_price_form" class="form" wire:submit.prevent="submit">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_price_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_price_header" data-kt-scroll-wrappers="#kt_modal_add_price_scroll" data-kt-scroll-offset="300px">
                        
                        <!-- Name -->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Name</label>
                            <input type="text" wire:model="name" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Name"/>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Type</label>
                            <select wire:model="type" name="type" class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="">Select</option>
                                <option value="area">Area</option>
                                <!--<option value="option">Option</option>-->
                                <option value="venue">Venue</option>
                            </select>
                            @error('type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Area Dropdown (conditional) -->
                        @if($type === 'area')
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Area</label>
                            <select wire:model.defer="area_id" name="area_id" class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="">Select Area</option>
                                @foreach($venueAreas as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                            @error('area_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif


                        <!-- Venue Dropdown (conditional) -->
                        @if($type === 'venue')
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Venue</label>
                            <select wire:model.defer="venue_id" name="venue_id" class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="">Select Venue</option>
                                @foreach($venues as $venue)
                                    <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                                @endforeach
                            </select>
                            @error('venue_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif


                        <!-- Price -->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Price</label>
                            <input type="text" wire:model="price" name="price" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Price"/>
                            @error('price')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tier Type -->
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Tier Type</label>
                            <input type="text" wire:model.defer="tier_type" name="tier_type" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Tier Type"/>
                            @error('tier_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tier Value -->
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Tier Value</label>
                            <input type="text" wire:model.defer="tier_value" name="tier_value" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Tier Value"/>
                            @error('tier_value')
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
