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