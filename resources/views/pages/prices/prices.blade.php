<x-default-layout>

    @section('title')
        {{ trans('prices.prices') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('prices') }}  <!-- Update breadcrumb -->
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search price" id="mySearchInput"/>
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <!--begin::Add price-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_price" data-kt-action="create_row">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        Add price
                    </button>
                    <!--end::Add price-->
                </div>
                <!--end::Toolbar-->

                <!--begin::Modal-->
                <livewire:price.add-price-modal></livewire:price.add-price-modal>
                <!--end::Modal-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <div class="table-responsive">
                {{ $dataTable->table() }}  <!-- Make sure you have a pricesDataTable -->
            </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['prices-table'].search(this.value).draw();  <!-- Update table name -->
            });
            document.addEventListener('livewire:load', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_price').modal('hide');  <!-- Update modal ID -->
                    window.LaravelDataTables['prices-table'].ajax.reload();  <!-- Update table name -->
                });
            });

            document.addEventListener('livewire:load', function () {
                document.querySelectorAll('[data-kt-action="create_row"]').forEach(function (element) {
                    element.addEventListener('click', function (e) {
                        Livewire.emit('create_price');
                    });
                });
            });
        </script>
    @endpush

</x-default-layout>
