<x-default-layout>
    @section('title')
    {{ trans('quotes.title') }}
    @endsection

    <!--begin::Breadcrumb-->
    @section('breadcrumbs')
        {{ Breadcrumbs::render('quotes.show', $quote) }}
    @endsection
<!--begin::Card toolbar-->
    <div class="d-flex flex-column flex-xl-row gap-10">
    <div class="card flex-grow-1">
        <!--begin::Body-->
        <div class="card-body p-lg-20">
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-xl-row">
                <!--begin::Content-->
                <div class="flex-lg-row-fluid">
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
                            <button class="btn btn-sm btn-success" id="book-quote" data-quote-id="{{ $quote->id }}">
                                {{ trans('quotes.book') }}
                            </button>
                            <!--end::Action-->
                            
                        </div>
                        <!--end::Top-->

                        <!--begin::Wrapper-->
                        <div class="m-0">
                            <!--begin::Label-->
                            <div class="fw-bold fs-3 text-gray-800 mb-8">{{ trans('quotes.title') }} #{{ $quote->quote_number }} v{{ $quote->version }}</div>
                            <!--end::Label-->

                            <!--begin::Row-->
                            <div class="row g-5 mb-11">
                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--end::Label-->
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">{{ trans('quotes.createdon') }}:</div>
                                    <!--end::Label-->

                                    <!--end::Col-->
                                    <div class="fw-bold fs-6 text-gray-800">{{ \Carbon\Carbon::parse($quote->created_at)->format('d F Y') }}</div>
                                    <!--end::Col-->
                                </div>
                                <!--end::Col-->

                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--end::Label-->
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">{{ trans('quotes.senton') }}:</div>
                                    <!--end::Label-->

                                    <!--end::Info-->
                                    <div
                                        class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                        <span class="pe-2">dd/mm/yyyy</span>

                                        <span class="fs-7 text-danger d-flex align-items-center">
                                            <span class="bullet bullet-dot bg-danger me-2"></span>

                                            {{ trans('quotes.expiring_in_days' , ['days' => "123"]) }}
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
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">{{ trans('quotes.issuedfor') }}:</div>
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
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">{{ trans('quotes.issuedby') }}:</div>
                                    <!--end::Label-->

                                    <!--end::Text-->
                                    <div class="fw-bold fs-6 text-gray-800">{{ $tenant->name }}</div>
                                    <!--end::Text-->

                                    <!--start::Description-->
                                    <div class="fw-semibold fs-7 text-gray-600">
                                        {{$tenant->address}} <br>
                                        {{$tenant->postcode}} {{$tenant->city}} <br>
                                        {{$tenant->stateprovince}}, {{$tenant->country}}
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
                                                <th class="min-w-175px pb-2">{{ trans('quotes.description') }}</th>
                                                <th class="min-w-100px text-end pb-2">{{ trans('quotes.quantity') }}</th>
                                                <th class="min-w-100px text-end pb-2">{{ trans('quotes.unit') }}</th>
                                                <th class="min-w-100px text-end pb-2">{{ trans('quotes.price') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr class="fw-bold text-gray-700 fs-5">
                                                <td class="d-flex align-items-center text-left pt-6">
                                                    <i class="fa fa-genderless text-danger fs-2 me-2"></i>
                                                    {{ $quote->eventType ? $quote->eventType->event_name : 'N/A' }} - {{ $quote->eventArea ? $quote->eventArea->name : 'N/A' }}
                                                </td>

                                                <td class="pt-6 text-end">1</td>
                                                <td class="pt-6 text-end">$ {{ number_format($quote->price_venue, 2) }}</td>
                                                <td class="pt-6 text-dark fw-bolder text-end">$ {{ number_format($quote->price_venue, 2) }}</td>
                                            </tr>
                                            <!-- Additional row for options and priceOption -->
                                            @php
                                                // Convert the price_options string into an array
                                                $priceOptionsArray = explode('|', $quote->price_options);
                                            @endphp



                                            @foreach($optionsWithValues as $index => $optionWithValue)
                                                @if(!($optionWithValue['type'] == 'yes_no' && $optionWithValue['value'] == 'no'))
                                                  @php
                                                    $price = floatval($priceOptionsArray[$index]);
                                                    $value = floatval($optionWithValue['value']);
                                                  @endphp
                                                  @if ($value != 0)
                                                    <tr class="fw-bold text-gray-700 fs-5">
                                                        <td class="d-flex align-items-center text-left pt-6">
                                                            <i class="fa fa-genderless text-danger fs-2 me-2"></i>
                                                            @if($optionWithValue['type'] == 'yes_no' && $optionWithValue['value'] == 'yes')
                                                                {{ $optionWithValue['option']->name }}
                                                            @else
                                                                {{ $optionWithValue['option']->name }}
                                                            @endif
                                                        </td>
                                                        <td class="pt-6 text-end">{{ $optionWithValue['value'] }}</td>
                                                        <td class="pt-6 text-end">$ {{ number_format( (float) $priceOptionsArray[$index] / (float) $optionWithValue['value'], 2) }}</td>
                                                        <td class="pt-6 text-dark fw-bolder text-end">
                                                            $ {{ isset($priceOptionsArray[$index]) ? number_format($priceOptionsArray[$index], 2) : 'N/A' }}
                                                        </td>
                                                    </tr>
                                                    @endif
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
                                            <div class="fw-semibold pe-10 text-gray-600 fs-7">{{ trans('quotes.subtotal') }}:</div>
                                            <!--end::Accountname-->

                                            <!--begin::Label-->
                                            <div class="text-end fw-bold fs-6 text-gray-800">$ {{ number_format($quote->calculated_price, 2) }}</div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Item-->
                                        @if($discount)
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack mb-3">
                                            <!--begin::Accountname-->
                                            <div class="fw-semibold pe-10 text-gray-600 fs-7">{{ trans('quotes.discount') }}</div>
                                            <!--end::Accountname-->

                                            <!--begin::Label-->
                                            <div class="text-end fw-bold fs-6 text-gray-800">{{$quote->discount}}</div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Item-->
                                        @endif
                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack mb-3">
                                            <!--begin::Accountname-->
                                            <div class="fw-semibold pe-10 text-gray-600 fs-7">{{ trans('quotes.vat') }}:</div>
                                            <!--end::Accountname-->

                                            <!--begin::Label-->
                                            <div class="text-end fw-bold fs-6 text-gray-800">0</div>
                                            <!--end::Label-->
                                        </div>
                                        <!--end::Item-->

                                        <!--begin::Item-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Code-->
                                            <div class="fw-semibold pe-10 text-gray-600 fs-7">{{ trans('quotes.total') }}:</div>
                                            <!--end::Code-->

                                            <!--begin::Label-->
                                            <div class="text-end fw-bold fs-6 text-gray-800">$ {{ number_format($quote->price, 2) }}</div>
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
            </div>
        </div>
    </div>
    <div class="card h-lg-100 min-w-md-350px">
         <!--begin::Body-->
        <div class="card-body">
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-xl-row">
                <!--begin::Sidebar-->
                <div class="m-0">
                    <!--begin::Invoice 2 sidebar-->
                    <div class="d-print-none border border-dashed border-gray-300 card-rounded p-9 bg-lighten">

                       
                        <!--begin::Title-->
                        <h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">{{ trans('quotes.status') }}</h6>
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
                        <h6 class="mb-8 fw-bolder text-gray-600 text-hover-primary">{{ trans('quotes.versionhistory') }}</h6>
                        <!--end::Title-->

                        <!--begin::Item-->
                        <div class="mb-6">
                            <!--begin::Table-->
                                <div class="table-responsive border-bottom mb-9">
                                    <table class="table mb-3">
                                        <thead>
                                            <tr class="border-bottom fs-6 fw-bold text-muted">
                                                <th class="pb-2">{{ trans('quotes.version') }}</th>
                                                <th class="text-center pb-2">{{ trans('quotes.date') }}</th>
                                                <th class="text-end pb-2">{{ trans('general.actions') }}</th>
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
                                                    <a href="{{ route('quotes.show', $quoteHistory) }}">{{ trans('general.view') }}</a>
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
        <livewire:quote.edit-quote-modal></livewire:quote.edit-quote-modal>
        <!--end::Modal-->
    </div>
    </div>
    @push('scripts')
        <script>
            document.querySelectorAll('[data-kt-action="update_row"]').forEach(function (element) {
                element.addEventListener('click', function () {
                    Livewire.emit('update_quote', this.getAttribute('data-kt-quote-id'));
                });
            });
            document.addEventListener('livewire:load', function () {
                Livewire.on('success', function () {
                    $('#kt_modal_edit_quote').modal('hide'); 
                    location.reload();
                });
            });
            document.addEventListener('DOMContentLoaded', function() {
            const bookButton = document.getElementById('book-quote');

            if (bookButton) {
                bookButton.addEventListener('click', function() {
                    const quoteId = bookButton.getAttribute('data-quote-id');
                    axios.post(`/quotes/${quoteId}/book`)
                        .then(function(response) {
                            if(response.status === 200) {
                                toastr.success(response.data.message);
                            } else {
                                // If status is not 200, handle it as an error
                                toastr.error('Something went wrong. Please try again.');
                            }
                        })
                        .catch(function(error) {
                            if (error.response && error.response.data && error.response.data.error) {
                                toastr.error(error.response.data.error);
                            } else {
                                // Generic error message for other types of errors
                                toastr.error('An error occurred. Please try again.');
                            }
                        });
                });
            }
        });

            
        </script>
    @endpush
</x-default-layout>