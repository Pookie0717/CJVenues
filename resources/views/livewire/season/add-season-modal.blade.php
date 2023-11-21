<div class="modal fade" id="kt_modal_add_season" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered mw-650px">
        <div class="modal-content">
            <div class="modal-header" id="kt_modal_add_season_header">
                <h2 class="fw-bold">{{ trans('seasons.addseason') }}</h2>
                <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
                    {!! getIcon('cross','fs-1') !!}
                </div>
            </div>
            <div class="modal-body flex-center  px-5 my-7">
                <form id="kt_modal_add_season_form" class="form" wire:submit.prevent="submit">
                    <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_season_scroll" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_season_header" data-kt-scroll-wrappers="#kt_modal_add_season_scroll" data-kt-scroll-offset="300px">
                        
                        <!-- Name -->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('seasons.name') }}</label>
                            <input type="text" wire:model.defer="name" name="name" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Name"/>
                            @error('name')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        <div class="row mb-7">
                        <div class="col">
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('seasons.datefrom') }}</label>
                            <div class="input-group" id="date_from_picker_basic" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                <input id="date_from_picker_input" type="text"  wire:model.defer="date_from" class="form-control" data-td-target="#date_from_picker"/>
                                <span class="input-group-text" data-td-target="#date_from_picker" data-td-toggle="datetimepicker">
                                    <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                            @error('date_from')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col">
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('seasons.dateto') }}</label>
                            <div class="input-group" id="date_to_picker_basic" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                <input id="date_to_picker_input" type="text"  wire:model.defer="date_to" class="form-control" data-td-target="#date_to_picker"/>
                                <span class="input-group-text" data-td-target="#date_to_picker" data-td-toggle="datetimepicker">
                                    <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>
                            @error('date_to')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        </div>

                        <label class="mb-2 required fw-semibold fs-6 mb-2">{{ trans('seasons.daysoftheweek') }}</label>
                        <div class="row mb-7">
                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Mon" id="flexCheckMon"  wire:model="selectedWeekdays"/>
                                    <label class="form-check-label" for="flexCheckMon">
                                        {{ trans('calendar.monday') }}
                                    </label>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Tue" id="flexCheckTue"  wire:model="selectedWeekdays"/>
                                    <label class="form-check-label" for="flexCheckTue">
                                        {{ trans('calendar.tuesday') }}
                                    </label>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Wed" id="flexCheckWed"  wire:model="selectedWeekdays"/>
                                    <label class="form-check-label" for="flexCheckWed">
                                        {{ trans('calendar.wednesday') }}
                                    </label>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Thu" id="flexCheckThu"  wire:model="selectedWeekdays"/>
                                    <label class="form-check-label" for="flexCheckThu">
                                        {{ trans('calendar.thursday') }}
                                    </label>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Fri" id="flexCheckFri"  wire:model="selectedWeekdays"/>
                                    <label class="form-check-label" for="flexCheckFri">
                                        {{ trans('calendar.friday') }}
                                    </label>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Sat" id="flexCheckSat"  wire:model="selectedWeekdays"/>
                                    <label class="form-check-label" for="flexCheckSat">
                                        {{ trans('calendar.saturday') }}
                                    </label>
                                </div>
                            </div>

                            <div class="col">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="Sun" id="flexCheckSun"  wire:model="selectedWeekdays"/>
                                    <label class="form-check-label" for="flexCheckSun">
                                        {{ trans('calendar.sunday') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        
                        <!-- Priority -->
                        <div class="fv-row mb-7">
                            <label class="required fw-semibold fs-6 mb-2">{{ trans('general.priority') }}</label>
                            <input type="number" wire:model.defer="priority" name="priority" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="Priority"/>
                            @error('priority')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>


                        
                    </div>
                    
                    <div class="text-center pt-15">
                        <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled">{{ trans('general.discard') }}</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label" wire:loading.remove>{{ trans('general.submit') }}</span>
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
<script>
        
document.getElementById('date_from_picker_input').addEventListener('change', function () {
    @this.set('date_from', this.value);
});

document.getElementById('date_to_picker_input').addEventListener('change', function () {
    @this.set('date_to', this.value);
});


        </script>
