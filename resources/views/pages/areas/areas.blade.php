<x-default-layout>

    @section('title')
        Venues Areas
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('areas') }}  <!-- Update breadcrumb -->
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search contact" id="mySearchInput"/>
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <!--begin::Add contact-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_venue_area">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        Add Venue Area
                    </button>
                    <!--end::Add contact-->
                </div>
                <!--end::Toolbar-->

                <!--begin::Modal-->
                <livewire:venue-area.add-venue-area-modal></livewire:venue-area.add-venue-area-modal>
                <livewire:venue-area.block-venue-area-modal></livewire:venue-block.add-venue-area-modal>
                <!--end::Modal-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <div class="table-responsive">
                {{ $dataTable->table() }}  <!-- Make sure you have a ContactsDataTable -->
            </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['areas-table'].search(this.value).draw();  <!-- Update table name -->
            });
            document.addEventListener('livewire:load', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_venue_area').modal('hide');  <!-- Update modal ID -->
                    window.LaravelDataTables['areas-table'].ajax.reload();  <!-- Update table name -->
                });
            });
        </script>
    @endpush

</x-default-layout>
