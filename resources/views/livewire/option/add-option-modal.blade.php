<div class="modal fade" id="kt_modal_add_option" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_option_header">
                <h2 class="fw-bold">Add Option</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body flex-center px-5 my-7">
                <form id="kt_modal_add_option_form" class="form" wire:submit.prevent="submit">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_option_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_option_header" data-kt-scroll-wrappers="#kt_modal_add_option_scroll" data-kt-scroll-offset="300px">
                        <!-- Name -->
                        <div class="row mb-7">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Name</label>
                                <input type="text" wire:model.defer="name" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Name"/>
                                @error('name')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Position</label>
                                <input type="number" wire:model.defer="position" name="position" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Position"/>
                                @error('position')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Description</label>
                            <input type="text" wire:model.defer="description" name="description" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Description"/>
                            @error('description')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        <!-- Season and Venue -->
                        <div class="row mb-7">
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Season</label>
                                <select wire:model.defer="season_ids" name="season_ids[]" class="form-select form-select-solid mb-3 mb-lg-0" multiple>
                                    @foreach($seasons as $season)
                                        <option value="{{ $season->id }}">{{ $season->name }}</option>
                                    @endforeach
                                </select>
                                @error('season_ids')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <label class="required fw-semibold fs-6 mb-2">Venue</label>
                                <select wire:model.defer="venue_ids" name="venue_ids[]" class="form-select form-select-solid mb-3 mb-lg-0" multiple>
                                    @foreach($venues as $venue)
                                        <option value="{{ $venue->id }}">{{ $venue->name }}</option>
                                    @endforeach
                                </select>
                                @error('venue_ids')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Type -->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">Type</label>
                            <select wire:model.defer="type" name="type" class="form-select form-select-solid mb-3 mb-lg-0">
                                <option value="">Select</option>
                                <option value="yes_no">Yes/No</option>
                                <option value="check">Check</option>
                                <option value="radio">Radio</option>
                                <option value="number">Number</option>
                                <option value="logic">Logic</option>
                            </select>
                            @error('type')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <!-- Logic -->
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Logic</label>

                            <div>
                                <!-- Add Condition Button -->
                                <button wire:click.prevent="addCondition" class="btn btn-sm btn-light mb-3">Add Condition</button>
                            </div>

                            <!-- List of Conditions -->
                            @foreach($conditions as $index => $condition)
                            @if ($index > 0)
                            <div class="input-group mb-3" wire:key="logical-operator-{{ $index }}">
                                <select wire:model="conditions.{{ $index }}.logical_operator" name="conditions[{{ $index }}][logical_operator]" class="form-select form-select-solid">
                                    <option value="AND">AND</option>
                                    <option value="OR">OR</option>
                                </select>
                            </div>
                            @endif
                            <div class="input-group mb-3" wire:key="condition-{{ $index }}">
                                <select wire:model="conditions.{{ $index }}.field" name="conditions[{{ $index }}][field]" class="form-select form-select-solid">
                                    <option value="people">People</option>
                                    <option value="hours">Hours</option>
                                    <option value="days">Days</option>
                                    <!-- Add more fields as needed -->
                                </select>
                                <select wire:model="conditions.{{ $index }}.operator" name="conditions[{{ $index }}][operator]" class="form-select form-select-solid">
                                    <option value="equals">equals</option>
                                    <option value="not_equals">not equals</option>
                                    <option value="less_than">less than</option>
                                    <option value="greater_than">greater than</option>
                                    <option value="less_than_or_equals">less than or equals</option>
                                    <option value="greater_than_or_equals">greater than or equals</option>
                                </select>
                                <input type="number" wire:model="conditions.{{ $index }}.value" name="conditions[{{ $index }}][value]" class="form-control form-control-solid" placeholder="Value">

                                <!-- Remove Condition Button -->
                                <button wire:click.prevent="removeCondition({{ $index }})" class="btn btn-sm btn-light" style="margin-left: 10px;">Remove</button>
                            </div>
                            @endforeach

                            @error('conditions.*.field') @error('conditions.*.operator') @error('conditions.*.value')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror @enderror @enderror
                        </div>

                        <!-- Values -->
                        <div class="fv-row mb-7">
                            <label class="fw-semibold fs-6 mb-2">Values (Separated by '|' if necessary)</label>
                            <textarea wire:model.defer="values" name="values" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Value1|Value2|Value3"></textarea>
                            @error('values')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>




                        
                        <!--begin::Input group-->
                        <div class="row mb-7">
                            <div class="col">
                                <!-- VAT -->
                                <label class="required fw-semibold fs-6 mb-2">VAT</label>
                                <div class="input-group mb-3">
                                    <input type="number" wire:model.defer="vat" name="vat" class="form-control" placeholder="VAT">
                                    <span class="input-group-text">%</span>
                                </div>
                                @error('vat')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="col">
                                <!-- Default Value -->
                                <label class="fw-semibold fs-6 mb-2">Default Value</label>
                                <input type="text" wire:model.defer="default_value" name="default_value" class="form-control form-control-solid" placeholder="Default Value">
                                @error('default_value')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <!--end::Input group-->


                        <!-- Always Included -->
                        <div class="fv-row mb-7">
                            <div class="form-check form-switch">
                                <input wire:model.defer="always_included" type="checkbox" class="form-check-input" id="always_included_checkbox">
                                <label class="form-check-label" for="always_included_checkbox">Include by Default</label>
                            </div>
                            @error('always_included')
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
