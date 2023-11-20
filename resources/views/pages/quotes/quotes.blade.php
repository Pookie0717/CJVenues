<x-default-layout>

    @section('title')
        {{ trans('quotes.quotes') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('quotes') }}  <!-- Update breadcrumb -->
    @endsection

    <div class="card">
        <!--begin::Card header-->
        <div class="card-header border-0 pt-6">
            <!--begin::Card title-->
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    {!! getIcon('magnifier', 'fs-3 position-absolute ms-5') !!}
                    <input type="text" data-kt-user-table-filter="search" class="form-control form-control-solid w-250px ps-13" placeholder="Search quote" id="mySearchInput"/>
                </div>
                <!--end::Search-->
            </div>
            <!--begin::Card title-->

            <!--begin::Card toolbar-->
            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-user-table-toolbar="base">
                    <!--begin::Add quote-->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_quote">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        {{ trans('quotes.addquote') }}
                    </button>
                    <!--end::Add quote-->
                </div>
                <!--end::Toolbar-->

                <!--begin::Modal-->
                <livewire:quote.add-quote-modal></livewire:quote.add-quote-modal>
                <!--end::Modal-->
            </div>
            <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body py-4">
            <!--begin::Table-->
            <div class="table-responsive">
                {{ $dataTable->table() }}  <!-- Make sure you have a QuotesDataTable -->
            </div>
            <!--end::Table-->
        </div>
        <!--end::Card body-->
    </div>

    @push('scripts')
        {{ $dataTable->scripts() }}
        <script>
            document.getElementById('mySearchInput').addEventListener('keyup', function () {
                window.LaravelDataTables['quotes-table'].search(this.value).draw();
            });
            document.addEventListener('livewire:load', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_quote').modal('hide'); 
                    window.LaravelDataTables['quotes-table'].ajax.reload(); 
                });
            });
        </script>
    @endpush

</x-default-layout>
