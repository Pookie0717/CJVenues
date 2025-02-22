<x-default-layout>

    @section('title')
        {{ trans('options.options') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('options') }}  <!-- Update breadcrumb -->
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search option" id="mySearchInput"/>
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <!--begin::Add option-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_option" data-kt-action="create_row">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        {{ trans('options.addoption') }}
                    </button>
                    <!--end::Add option-->
                </div>
                <!--end::Toolbar-->

                <!--begin::Modal-->
                <livewire:option.add-option-modal></livewire:option.add-option-modal>
                <!--end::Modal-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <div class="table-responsive">
                {{ $dataTable->table() }}  <!-- Make sure you have a optionsDataTable -->
            </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['options-table'].search(this.value).draw();  <!-- Update table name -->
            });
            
            document.addEventListener('livewire:load', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_option').modal('hide');  <!-- Update modal ID -->
                    window.LaravelDataTables['options-table'].ajax.reload();  <!-- Update table name -->
                });
            });

            document.addEventListener('livewire:load', function () {
                document.querySelectorAll('[data-kt-action="create_row"]').forEach(function (element) {
                    element.addEventListener('click', function (e) {
                        Livewire.emit('create_option');
                    });
                });
            });
        </script>
    @endpush

</x-default-layout>
