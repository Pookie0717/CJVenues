<x-default-layout>
    @section('title')
    Quote
    @endsection

    <!--begin::Breadcrumb-->
    @section('breadcrumbs')
    {{ Breadcrumbs::render('quotes') }}
    @endsection
<!--begin::Card toolbar-->
    <div class="card">
        <!--begin::Body-->
        <div class="card-body p-lg-20">
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-xl-row">
                <!--begin::Content-->
                <div class="flex-lg-row-fluid me-xl-18 mb-10 mb-xl-0">
                    <!--begin::Invoice 2 content-->
                    <div class="mt-n1">
                        <!--begin::Top-->
                        <div class="d-flex flex-stack pb-10">
                            <!--begin::Logo-->
                            <a href="#">
                                <img alt="Logo" src="{{ image('logos/default-dark.svg') }}" class="h-25px app-sidebar-logo-default" />
                            </a>
                            <!--end::Logo-->

                            <!--begin::Action-->
                            <a href="#" class="btn btn-sm btn-success disabled">Invoice</a>
                            <!--end::Action-->
                        </div>
                        <!--end::Top-->

                        <!--begin::Wrapper-->
                        <div class="m-0">
                            <!--begin::Label-->
                            <div class="fw-bold fs-3 text-gray-800 mb-8">Quote #{{ $quote->quote_number }} v{{ $quote->version }}</div>
                            <!--end::Label-->

                            <!--begin::Row-->
                            <div class="row g-5 mb-11">
                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--end::Label-->
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Created On:</div>
                                    <!--end::Label-->

                                    <!--end::Col-->
                                    <div class="fw-bold fs-6 text-gray-800">{{ \Carbon\Carbon::parse($quote->created_at)->format('d F Y') }}</div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Col-->

                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--end::Label-->
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Sent On:</div>
                                    <!--end::Label-->

                                    <!--end::Info-->
                                    <div
                                        class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                        <span class="pe-2">dd/mm/yyyy</span>

                                        <span class="fs-7 text-danger d-flex align-items-center">
                                            <span class="bullet bullet-dot bg-danger me-2"></span>

                                            Expiring in x days
                                        </span>
                                    </div>
                                    <!--end::Info-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->

                            <!--begin::Row-->
                            <div class="row g-5 mb-12">
                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--end::Label-->
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Issue For:</div>
                                    <!--end::Label-->

                                    @foreach ($associatedContact as $contact)
                                    <!--start::Text-->
                                    <div class="fw-bold fs-6 text-gray-800">{{ $contact->name }}</div>
                                    <!--end::Text-->

                                    <!--start::Description-->
                                    <div class="fw-semibold fs-7 text-gray-600">
                                        {{$contact->address}} <br>
                                        {{$contact->postcode}} {{$contact->city}} <br>
                                        {{$contact->state}}, {{$contact->country}}
                                    </div>
                                    <!--end::Description-->
                                    @endforeach

                                </div>
                                <!--end::Col-->

                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--end::Label-->
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">Issued By:</div>
                                    <!--end::Label-->

                                    <!--end::Text-->
                                    <div class="fw-bold fs-6 text-gray-800">Coco and Jay LLC</div>
                                    <!--end::Text-->

                                    <!--end::Description-->
                                    <div class="fw-semibold fs-7 text-gray-600">
                                        30 N Gold St Ste R <br>
                                        28104 Sheridan <br>
                                        WY, United States
                                    </div>
                                    <!--end::Description-->
                                </div>
                                <!--end::Col-->
                            </div>
                            <!--end::Row-->

                            <!--begin::Content-->
                            <div class="flex-grow-1">
                                <!--begin::Table-->
                                <div class="table-responsive border-bottom mb-9">
                                    <table class="table mb-3">
                                        <thead>
                                            <tr class="border-bottom fs-6 fw-bold text-muted">
                                                <th class="min-w-175px pb-2">Description</th>
                                                <th class="min-w-100px text-end pb-2">Amount</th>
                                                <th class="min-w-100px text-end pb-2">Price</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr class="fw-bold text-gray-700 fs-5">
                                                <td class="d-flex align-items-center text-left pt-6">
                                                    <i class="fa fa-genderless text-danger fs-2 me-2"></i>
                                                    {{ $quote->eventType ? $quote->eventType->name : 'N/A' }} - {{ $quote->eventArea ? $quote->eventArea->name : 'N/A' }}
                                                </td>

                                                <td class="pt-6 text-end">N/A</td>
                                                <td class="pt-6 text-dark fw-bolder text-end">${{ number_format($quote->price_venue, 2) }}</td>
                                            </tr>
                                            <!-- Additional row for options and priceOption -->
                                            @php
                                                // Convert the price_options string into an array
                                                $priceOptionsArray = explode('|', $quote->price_options);
                                            @endphp

                                            @foreach($optionsWithValues as $index => $optionWithValue)
                                                @if(!($optionWithValue['type'] == 'yes_no' && $optionWithValue['value'] == 'no'))
                                                    <tr class="fw-bold text-gray-700 fs-5">
                                                        <td class="d-flex align-items-center text-left pt-6">
                                                            <i class="fa fa-genderless text-danger fs-2 me-2"></i>
                                                            @if($optionWithValue['type'] == 'yes_no' && $optionWithValue['value'] == 'yes')
                                                                {{ $optionWithValue['option']->name }}
                                                            @else
                                                                {{ $optionWithValue['option']->name }} - {{ $optionWithValue['value'] }}
                                                            @endif
                                                        </td>
                                                        <td class="pt-6 text-end">N/A</td>
                                                        <td class="pt-6 text-dark fw-bolder text-end">
                                                            $ {{ isset($priceOptionsArray[$index]) ? number_format($priceOptionsArray[$index], 2) : 'N/A' }}
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <!--end::Table-->

                                <!--begin::Container-->
                                <div class="d-flex justify-content-end">
                                    <!--begin::Section-->
                                    <div class="mw-300px">
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack mb-3">
                                            <!--begin::Accountname-->
                                            <div class="fw-semibold pe-10 text-gray-600 fs-7">Subtotal:</div>
                                            <!--end::Accountname-->

                                            <!--begin::Label-->
                                            <div class="text-end fw-bold fs-6 text-gray-800">${{ number_format($quote->price, 2) }}</div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Item-->

                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack mb-3">
                                            <!--begin::Accountname-->
                                            <div class="fw-semibold pe-10 text-gray-600 fs-7">VAT 0%</div>
                                            <!--end::Accountname-->

                                            <!--begin::Label-->
                                            <div class="text-end fw-bold fs-6 text-gray-800">0</div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Item-->

                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Code-->
                                            <div class="fw-semibold pe-10 text-gray-600 fs-7">Total</div>
                                            <!--end::Code-->

                                            <!--begin::Label-->
                                            <div class="text-end fw-bold fs-6 text-gray-800">${{ number_format($quote->price, 2) }}</div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Item-->
                                    </div>
                                    <!--end::Section-->
                                </div>
                                <!--end::Container-->
                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Wrapper-->
                    </div>
                    <!--end::Invoice 2 content-->
                </div>
                <!--end::Content-->

                <!--begin::Sidebar-->
                <div class="m-0">
                    <!--begin::Invoice 2 sidebar-->
                    <div class="d-print-none border border-dashed border-gray-300 card-rounded h-lg-100 min-w-md-350px p-9 bg-lighten">
                        <!--begin::Title-->
                        <h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">STATUS</h6>
                        <!--end::Title-->

                        <div class="mb-8">
                            @switch($quote->status)
                                @case('Sent')
                                    <span class="badge badge-primary">{{ $quote->status }}</span>
                                    @break
                                @case('Approved')
                                    <span class="badge badge-success">{{ $quote->status }}</span>
                                    @break
                                @case('Rejected')
                                    <span class="badge badge-danger">{{ $quote->status }}</span>
                                    @break
                                @default
                                    <span class="badge badge-secondary">{{ $quote->status }}</span>
                            @endswitch
                        </div>
                        <!--end::Labels-->

                        <!--begin::Title-->
                        <h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">VERSION HISTORY</h6>
                        <!--end::Title-->

                        <!--begin::Item-->
                        <div class="mb-6">
                            <!--begin::Table-->
                                <div class="table-responsive border-bottom mb-9">
                                    <table class="table mb-3">
                                        <thead>
                                            <tr class="border-bottom fs-6 fw-bold text-muted">
                                                <th class="pb-2">Version</th>
                                                <th class="text-center pb-2">Date</th>
                                                <th class="text-end pb-2">Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                          @foreach($relatedQuotes as $key => $quoteHistory)
                                            <tr class="fw-bold text-gray-700 fs-5 text-end">
                                                <td class="d-flex align-items-center pt-6">
                                                    <i class="fa fa-genderless text-danger fs-2 me-2"></i>
                                                    {{ $quoteHistory->version }}
                                                </td>
                                                <td class="pt-6 text-dark text-center fw-bolder">
                                                    @if ($key == 0)
                                                        <!-- Handle the first item (shifted from the last) -->
                                                        {{ $relatedQuotes[count($relatedQuotes) - 1]->created_at->format('d-m-Y H:i:s') }}
                                                    @else
                                                        {{ $relatedQuotes[$key - 1]->created_at->format('d-m-Y H:i:s') }}
                                                    @endif
                                                </td>
                                                <td class="pt-6 text-dark fw-bolder">
                                                    <a href="{{ route('quotes.show', $quoteHistory) }}">View</a>
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                    </table>
                                </div>
                                <!--end::Table-->
                        </div>
                        <!--end::Item-->
                    </div>
                    <!--end::Invoice 2 sidebar-->
                </div>
                <!--end::Sidebar-->
            </div>
            <!--end::Layout-->
        </div>
        <!--end::Body-->
        <!--begin::Modal-->
                <livewire:quote.add-quote-modal></livewire:quote.add-quote-modal>
                <!--end::Modal-->
    </div>
    @push('scripts')
    
        <script>
            // Add click event listener to update buttons
            document.querySelectorAll('[data-kt-action="update_row"]').forEach(function (element) {
                element.addEventListener('click', function () {
                    Livewire.emit('update_quote', this.getAttribute('data-kt-quote-id'));
                });
            });
            document.addEventListener('livewire:load', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_add_quote').modal('hide'); 
                    location.reload();
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
