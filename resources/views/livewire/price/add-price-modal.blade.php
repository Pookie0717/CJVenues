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
                                <option value="option">Option</option>
                                <option value="venue">Venue</option>
                                <!--<option value="per_person">Per Person</option>
                                <option value="pp_tier">Per Tier</option>-->
                            </select>
                            @error('type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Tier Dropdown (conditional) -->
                        @if($type === 'pp_tier')
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Tier</label>
                            <input type="text" id="tier_type_input" class="form-select form-select-solid mb-3 mb-lg-0" name="tier_type" wire:model="tier_type" placeholder="i.e. 1-100" class="form-control">
                            @error('pp_tier')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif

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

                        <!-- Option Dropdown (conditional) -->
                        @if($type === 'option')
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Option</label>
                            <select wire:model.defer="option_id" name="option_id" class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="">Select Option</option>
                                @foreach($options as $option)
                                    <option value="{{ $option->id }}">{{ $option->name }}</option>
                                @endforeach
                            </select>
                            @error('option_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif

                        <!-- Season Dropdown -->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Season</label>
                            <select wire:model.defer="season_id" name="season_id" class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="">Select Season</option>
                                @foreach($seasons as $season)
                                    <option value="{{ $season->id }}">{{ $season->name }}</option>
                                @endforeach
                            </select>
                            @error('season_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="row fv-row mb-7">
                        <!-- Price -->

                        <div class="col mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Apply price to</label>
                            <select wire:model="extra_tier_type" name="extra_tier_type" class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="">Select</option>
                                <option value="buffer_before">Buffer Before</option>
                                <option value="buffer_after">Buffer After</option>
                                <option value="event">Event</option>
                            </select>
                            @error('extra_tier_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        
                        <div class="col mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Price</label>
                            <input type="text" wire:model="price" name="price" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Price"/>
                            @error('price')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                         <!-- Multiplier -->
                        <div class="col mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Multiplier</label>
                            <select wire:model="multiplier" name="multiplier" class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="">Select</option>
                                <option value="event">Per Event</option>
                                <option value="event_pp">Per Person</option>
                                <option value="daily">Per Day</option>
                                <option value="daily_pp">Per Day Per Person</option>
                                <option value="hourly">Per Hour</option>
                                <option value="hourly_pp">Per Hour Per Person</option>
                            </select>
                            @error('multiplier')
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
