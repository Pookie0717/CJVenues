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
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_quote" data-kt-action="create_row">
                        {!! getIcon('plus', 'fs-2', '', 'i') !!}
                        {{ trans('quotes.addquote') }}
                    </button>
                    <!--end::Add quote-->
                </div>
                <!--end::Toolbar-->

                <!--begin::Modal-->
                <livewire:quote.add-quote-modal></livewire:quote.add-quote-modal>
                <!--end::Modal-->

                 <!--begin::Modal-->
                 <livewire:quote.add-contact-modal></livewire:quotep.add-contact-modal>
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
            document.addEventListener('livewire:load', function () {
                document.querySelectorAll('[data-kt-action="create_row"]').forEach(function (element) {
                    element.addEventListener('click', function (e) {
                        Livewire.emit('create_quote');
                    });
                });
            });

            var elements = Array.prototype.slice.call(document.querySelectorAll("[data-bs-stacked-modal]"));

            if (elements && elements.length > 0) {
                elements.forEach((element) => {
                    if (element.getAttribute("data-kt-initialized") === "1") {
                        return;
                    }

                    element.setAttribute("data-kt-initialized", "1");

                    element.addEventListener("click", function(e) {
                        e.preventDefault();

                        const modalEl = document.querySelector(this.getAttribute("data-bs-stacked-modal"));

                        if (modalEl) {
                            const modal = new bootstrap.Modal(modalEl);
                            modal.show();
                        }
                    });
                });
            }

            // Make the DIV element draggable:
            var element = document.querySelector('#kt_modal_add_contact');
            dragElement(element);

            function dragElement(elmnt) {
                var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
                if (elmnt.querySelector('.modal-header')) {
                    // if present, the header is where you move the DIV from:
                    elmnt.querySelector('.modal-header').onmousedown = dragMouseDown;
                } else {
                    // otherwise, move the DIV from anywhere inside the DIV:
                    elmnt.onmousedown = dragMouseDown;
                }

                function dragMouseDown(e) {
                    e = e || window.event;
                    e.preventDefault();
                    // get the mouse cursor position at startup:
                    pos3 = e.clientX;
                    pos4 = e.clientY;
                    document.onmouseup = closeDragElement;
                    // call a function whenever the cursor moves:
                    document.onmousemove = elementDrag;
                }

                function elementDrag(e) {
                    e = e || window.event;
                    e.preventDefault();
                    // calculate the new cursor position:
                    pos1 = pos3 - e.clientX;
                    pos2 = pos4 - e.clientY;
                    pos3 = e.clientX;
                    pos4 = e.clientY;
                    // set the element's new position:
                    elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
                    elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
                }

                function closeDragElement() {
                    // stop moving when mouse button is released:
                    document.onmouseup = null;
                    document.onmousemove = null;
                }
            }
        </script>
    @endpush

</x-default-layout>
