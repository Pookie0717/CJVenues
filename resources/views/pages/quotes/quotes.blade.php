<x-default-layout>

    @section('title')
        Quotes
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
                        Add Quote
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
                window.LaravelDataTables['quotes-table'].search(this.value).draw();  <!-- Update table name -->
            });
            document.addEventListener('livewire:load', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_quote').modal('hide');  <!-- Update modal ID -->
                    window.LaravelDataTables['quotes-table'].ajax.reload();  <!-- Update table name -->
                });
            });

            new tempusDominus.TempusDominus(document.getElementById("time_to_picker_basic"), {
                display: {
                    viewMode: "clock",
                    components: {
                        decades: false,
                        year: false,
                        month: false,
                        date: false,
                        hours: true,
                        minutes: true,
                        seconds: false
                    }
                },
                localization: {
                    locale: "us",
                    format: "HH:ss"
                }
            });
            new tempusDominus.TempusDominus(document.getElementById("time_from_picker_basic"), {
                display: {
                    viewMode: "clock",
                    components: {
                        decades: false,
                        year: false,
                        month: false,
                        date: false,
                        hours: true,
                        minutes: true,
                        seconds: false
                    }
                },
                localization: {
                    locale: "us",
                    format: "HH:ss"
                }
            });
            new tempusDominus.TempusDominus(document.getElementById("date_from_picker_basic"), {
                display: {
                    viewMode: "calendar",
                    components: {
                        decades: true,
                        year: true,
                        month: true,
                        date: true,
                        hours: false,
                        minutes: false,
                        seconds: false
                    }
                },
                localization: {
                    locale: "us",
                    startOfTheWeek: 1,
                    format: "dd-MM-yyyy"
                }
            });
            new tempusDominus.TempusDominus(document.getElementById("date_to_picker_basic"), {
                display: {
                    viewMode: "calendar",
                    components: {
                        decades: true,
                        year: true,
                        month: true,
                        date: true,
                        hours: false,
                        minutes: false,
                        seconds: false
                    }
                },
                localization: {
                    locale: "us",
                    startOfTheWeek: 1,
                    format: "dd-MM-yyyy"
                }
            });
        </script>
    @endpush

</x-default-layout>
