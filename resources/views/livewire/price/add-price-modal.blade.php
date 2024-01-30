<div class="modal fade" id="kt_modal_add_price" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_price_header">
                <h2 class="fw-bold">{{ trans('prices.addprice') }}</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body flex-center  px-5 my-7">
                <form id="kt_modal_add_price_form" class="form" wire:submit.prevent="submit">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_price_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_price_header" data-kt-scroll-wrappers="#kt_modal_add_price_scroll" data-kt-scroll-offset="300px">
                        
                        <!-- Name -->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('prices.name') }}</label>
                            <input type="text" wire:model="name" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Name"/>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Type -->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('prices.type') }}</label>
                            <select wire:model="type" name="type" class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="">{{ trans('general.select') }}</option>
                                <option value="area">{{ trans('prices.type_area') }}</option>
                                <option value="option">{{ trans('prices.type_option') }}</option>
                                <option value="venue">{{ trans('prices.type_venue') }}</option>
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
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('prices.tier') }}</label>
                            <input type="text" id="tier_type_input" class="form-select form-select-solid mb-3 mb-lg-0" name="tier_type" wire:model="tier_type" placeholder="i.e. 1-100" class="form-control">
                            @error('pp_tier')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        @endif

                        <!-- Area Dropdown (conditional) -->
                        @if($type === 'area')
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('prices.area') }}</label>
                            <select wire:model.defer="area_id" name="area_id" class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="">{{ trans('prices.selectarea') }}</option>
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
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('prices.venue') }}</label>
                            <select wire:model.defer="venue_id" name="venue_id" class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="">{{ trans('prices.selectvenue') }}</option>
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
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('prices.option') }}</label>
                            <select wire:model="option_id" name="option_id" class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="">{{ trans('prices.selectoption') }}</option>
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
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('prices.season') }}</label>
                            <select wire:model.defer="season_id" name="season_id" class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="">{{ trans('prices.selectseason') }}</option>
                                @foreach($seasons as $season)
                                    <option value="{{ $season->id }}">{{ $season->name }}</option>
                                @endforeach
                            </select>
                            @error('season_id')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                         <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('prices.applypriceto') }}</label>
                            <select wire:model="extra_tier_type" name="extra_tier_type[]" class="form-select form-select-solid mb-3 mb-lg-0" multiple>
                                <option value="buffer_before">{{ trans('prices.bufferbefore') }}</option>
                                <option value="buffer_after">{{ trans('prices.bufferafter') }}</option>
                                <option value="event">{{ trans('prices.event') }}</option>
                            </select>
                            @error('extra_tier_type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <div class="row fv-row mb-7">
                            <!-- Price -->
                            
                            <div class="col mb-7">
                                <label class="required fw-semibold fs-6 mb-2">{{ trans('prices.price') }}</label>
                                <input type="text" wire:model="price" name="price" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Price"/>
                                @error('price')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Multiplier -->
                            <div class="col mb-7">
                                <label class="required fw-semibold fs-6 mb-2">{{ trans('prices.multiplier') }}</label>
                                <select wire:model="multiplier" name="multiplier" class="form-select form-select-solid mb-3 mb-lg-0">
                                    <option value="">{{ trans('general.select') }}</option>
                                    <option value="event">{{ trans('prices.multiplier_perevent') }}</option>
                                    <option value="event_pp">{{ trans('prices.multiplier_perperson') }}</option>
                                    <option value="daily">{{ trans('prices.multiplier_perday') }}</option>
                                    <option value="daily_pp">{{ trans('prices.multiplier_perdayperperson') }}</option>
                                    <option value="hourly">{{ trans('prices.multiplier_perhour') }}</option>
                                    <option value="hourly_pp">{{ trans('prices.multiplier_perhourperperson') }}</option>
                                    <option value="every_x_p">{{ trans('prices.multiplier_everyxpeople') }}</option>
                                    <option value="every_x_d">{{ trans('prices.multiplier_everyxdays') }}</option>
                                    <option value="every_x_h">{{ trans('prices.multiplier_everyxhours') }}</option>
                                </select>
                                @error('multiplier')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col mb-7 {{$dX? '': 'd-none'}}">
                                <label class="required fw-semibold fs-6 mb-2">Value of x</label>
                                <input type="text" wire:model="x" name="x" class="form-control form-control-solid mb-3 mb-lg-0" placeholder=""/>
                                @error('x')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                    </div>
                    
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">{{ trans('general.discard') }}</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label" wire:loading.remove wire:target="submit">{{ trans('general.submit') }}</span>
                            <span class="indicator-progress" wire:loading wire:target="submit">
                                {{ trans('general.pleasewait') }}...
                                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
