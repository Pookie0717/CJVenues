<div class="modal fade" id="kt_modal_add_quote" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <!--begin::Modal dialog-->
    <div class="modal-dialog modal-fullscreen">
        <!--begin::Modal content-->
        <div class="modal-content">
            <!--begin::Modal header-->
            <div class="modal-header" id="kt_modal_add_quote_header">
                <!--begin::Modal title-->
                <h2 class="fw-bold">Add Quote</h2>
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
                <!--begin::Stepper-->
<div class="stepper stepper-pills" id="kt_stepper_example_basic">
<!--begin::Nav-->
<div class="stepper-nav flex-center flex-wrap mb-10">
    <!--begin::Step 1-->
    <div class="stepper-item mx-8 my-4 current" data-kt-stepper-element="nav">
        <!--begin::Wrapper-->
        <div class="stepper-wrapper d-flex align-items-center">
            <!--begin::Icon-->
            <div class="stepper-icon w-40px h-40px">
                <i class="stepper-check fas fa-check"></i>
                <span class="stepper-number">1</span>
            </div>
            <!--end::Icon-->

            <!--begin::Label-->
            <div class="stepper-label">
                <h3 class="stepper-title">
                    Step 1
                </h3>

                <div class="stepper-desc">
                    Description
                </div>
            </div>
            <!--end::Label-->
        </div>
        <!--end::Wrapper-->

        <!--begin::Line-->
        <div class="stepper-line h-40px"></div>
        <!--end::Line-->
    </div>
    <!--end::Step 1-->

    <!--begin::Step 2-->
    <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav">
        <!--begin::Wrapper-->
        <div class="stepper-wrapper d-flex align-items-center">
             <!--begin::Icon-->
            <div class="stepper-icon w-40px h-40px">
                <i class="stepper-check fas fa-check"></i>
                <span class="stepper-number">2</span>
            </div>
            <!--begin::Icon-->

            <!--begin::Label-->
            <div class="stepper-label">
                <h3 class="stepper-title">
                    Step 2
                </h3>

                <div class="stepper-desc">
                    Description
                </div>
            </div>
            <!--end::Label-->
        </div>
        <!--end::Wrapper-->

        <!--begin::Line-->
        <div class="stepper-line h-40px"></div>
        <!--end::Line-->
    </div>
    <!--end::Step 2-->

    <!--begin::Step 3-->
    <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav">
        <!--begin::Wrapper-->
        <div class="stepper-wrapper d-flex align-items-center">
             <!--begin::Icon-->
            <div class="stepper-icon w-40px h-40px">
                <i class="stepper-check fas fa-check"></i>
                <span class="stepper-number">3</span>
            </div>
            <!--begin::Icon-->

            <!--begin::Label-->
            <div class="stepper-label">
                <h3 class="stepper-title">
                    Step 3
                </h3>

                <div class="stepper-desc">
                    Description
                </div>
            </div>
            <!--end::Label-->
        </div>
        <!--end::Wrapper-->

        <!--begin::Line-->
        <div class="stepper-line h-40px"></div>
        <!--end::Line-->
    </div>
    <!--end::Step 3-->

    <!--begin::Step 4-->
    <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav">
        <!--begin::Wrapper-->
        <div class="stepper-wrapper d-flex align-items-center">
             <!--begin::Icon-->
            <div class="stepper-icon w-40px h-40px">
                <i class="stepper-check fas fa-check"></i>
                <span class="stepper-number">4</span>
            </div>
            <!--begin::Icon-->

            <!--begin::Label-->
            <div class="stepper-label">
                <h3 class="stepper-title">
                    Step 4
                </h3>

                <div class="stepper-desc">
                    Description
                </div>
            </div>
            <!--end::Label-->
        </div>
        <!--end::Wrapper-->

        <!--begin::Line-->
        <div class="stepper-line h-40px"></div>
        <!--end::Line-->
    </div>
    <!--end::Step 4-->
</div>
<!--end::Nav-->

    <!--begin::Form-->
    <form class="form w-lg-750px mx-auto" novalidate="novalidate" id="kt_stepper_example_basic_form">
        <!--begin::Group-->
        <div class="mb-5">
            <!--begin::Step 1-->
            <div class="flex-column current" data-kt-stepper-element="content">
                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <!--begin::Label-->
                    <label class="form-label">Example Label 1</label>
                    <!--end::Label-->

                    <!--begin::Input-->
                    <input type="text" class="form-control form-control-solid" name="input1" placeholder="" value=""/>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <!--begin::Label-->
                    <label class="form-label">Example Label 2</label>
                    <!--end::Label-->

                    <!--begin::Input-->
                    <input type="text" class="form-control form-control-solid" name="input2" placeholder="" value=""/>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <!--begin::Label-->
                    <label class="form-label">Example Label 3</label>
                    <!--end::Label-->

                    <!--begin::Switch-->
                    <label class="form-check form-switch form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" checked="checked" value="1"/>
                        <span class="form-check-label">
                            Switch
                        </span>
                    </label>
                    <!--end::Switch-->
                </div>
                <!--end::Input group-->
            </div>
            <!--begin::Step 1-->

            <!--begin::Step 1-->
            <div class="flex-column" data-kt-stepper-element="content">
                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <!--begin::Label-->
                    <label class="form-label">Example Label 1</label>
                    <!--end::Label-->

                    <!--begin::Input-->
                    <input type="text" class="form-control form-control-solid" name="input1" placeholder="" value=""/>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <!--begin::Label-->
                    <label class="form-label">Example Label 2</label>
                    <!--end::Label-->

                    <!--begin::Input-->
                    <textarea class="form-control form-control-solid" rows="3" name="input2" placeholder=""></textarea>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <!--begin::Label-->
                    <label class="form-label">Example Label 3</label>
                    <!--end::Label-->

                    <!--begin::Input-->
                    <label class="form-check form-check-custom form-check-solid">
                        <input class="form-check-input" checked="checked" type="checkbox" value="1"/>
                        <span class="form-check-label">
                            Checkbox
                        </span>
                    </label>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->
            </div>
            <!--begin::Step 1-->

            <!--begin::Step 1-->
            <div class="flex-column" data-kt-stepper-element="content">
                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <!--begin::Label-->
                    <label class="form-label d-flex align-items-center">
                        <span class="required">Input 1</span>
                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Example tooltip"></i>
                    </label>
                    <!--end::Label-->

                    <!--begin::Input-->
                    <input type="text" class="form-control form-control-solid" name="input1" placeholder="" value=""/>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <!--begin::Label-->
                    <label class="form-label">
                        Input 2
                    </label>
                    <!--end::Label-->

                    <!--begin::Input-->
                    <input type="text" class="form-control form-control-solid" name="input2" placeholder="" value=""/>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->
            </div>
            <!--begin::Step 1-->

            <!--begin::Step 1-->
            <div class="flex-column" data-kt-stepper-element="content">
                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <!--begin::Label-->
                    <label class="form-label d-flex align-items-center">
                        <span class="required">Input 1</span>
                        <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip" title="Example tooltip"></i>
                    </label>
                    <!--end::Label-->

                    <!--begin::Input-->
                    <input type="text" class="form-control form-control-solid" name="input1" placeholder="" value=""/>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <!--begin::Label-->
                    <label class="form-label">
                        Input 2
                    </label>
                    <!--end::Label-->

                    <!--begin::Input-->
                    <input type="text" class="form-control form-control-solid" name="input2" placeholder="" value=""/>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->

                <!--begin::Input group-->
                <div class="fv-row mb-10">
                    <!--begin::Label-->
                    <label class="form-label">
                        Input 3
                    </label>
                    <!--end::Label-->

                    <!--begin::Input-->
                    <input type="text" class="form-control form-control-solid" name="input3" placeholder="" value=""/>
                    <!--end::Input-->
                </div>
                <!--end::Input group-->
            </div>
            <!--begin::Step 1-->
        </div>
        <!--end::Group-->

        <!--begin::Actions-->
        <div class="d-flex flex-stack">
            <!--begin::Wrapper-->
            <div class="me-2">
                <button type="button" class="btn btn-light btn-active-light-primary" data-kt-stepper-action="previous">
                    Back
                </button>
            </div>
            <!--end::Wrapper-->

            <!--begin::Wrapper-->
            <div>
                <button type="button" class="btn btn-primary" data-kt-stepper-action="submit">
                    <span class="indicator-label">
                        Submit
                    </span>
                    <span class="indicator-progress">
                        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                    </span>
                </button>

                <button type="button" class="btn btn-primary" data-kt-stepper-action="next">
                    Continue
                </button>
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Actions-->
    </form>
    <!--end::Form-->
</div>
<!--end::Stepper-->
                <!--end::Form-->
            </div>
            <!--end::Modal body-->
        </div>
        <!--end::Modal content-->
    </div>
    <!--end::Modal dialog-->
</div>