@extends('layout.public')

@section('title')
{{ trans('quote.title') }}
@endsection

<!--begin::Breadcrumb-->
@section('breadcrumbs')
    {{ Breadcrumbs::render('quotes.show', $quote) }}
@endsection
<!--begin::Card toolbar-->
<div class="d-flex flex-column flex-xl-row gap-10" style="padding:30px;">
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
                    </div>
                    <!--end::Top-->

                    <!--begin::Wrapper-->
                    <div class="m-0">
                        <!--begin::Label-->
                        <div class="fw-bold fs-3 text-gray-800 mb-8">{{ trans('quotes.quote_label', ['number' => $quote->quote_number, 'version' => $quote->version,]) }}</div>
                        <!--end::Label-->

                        <!--begin::Row-->
                        <div class="row g-5 mb-11">
                            <!--end::Col-->
                            <div class="col-sm-6">
                                <!--end::Label-->
                                <div class="fw-semibold fs-7 text-gray-600 mb-1">{{ trans('quotes.createdon') }}</div>
                                <!--end::Label-->

                                <!--end::Col-->
                                <div class="fw-bold fs-6 text-gray-800">{{ \Carbon\Carbon::parse($quote->created_at)->format('d F Y') }}</div>
                                <!--end::Col-->
                            </div>
                            <!--end::Col-->

                            <!--end::Col-->
                            <div class="col-sm-6">
                                <!--end::Label-->
                                <div class="fw-semibold fs-7 text-gray-600 mb-1">{{ trans('quotes.senton') }}</div>
                                <!--end::Label-->

                                <!--end::Info-->
                                <div
                                    class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                    <span class="pe-2">dd/mm/yyyy</span>

                                    <span class="fs-7 text-danger d-flex align-items-center">
                                        <span class="bullet bullet-dot bg-danger me-2"></span>

                                        {{ trans('quotes.expiringindays' , ['days' => "x"]) }}
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
                                <div class="fw-semibold fs-7 text-gray-600 mb-1">{{ trans('quotes.issuedfor') }}</div>
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
                                <div class="fw-semibold fs-7 text-gray-600 mb-1">{{ trans('quotes.issuedby') }}</div>
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
                                <!-- Table Content Unchanged -->
                            </div>
                            <!--end::Table-->

                            <!--begin::Container-->
                            <div class="d-flex justify-content-end">
                                <!--begin::Section-->
                                <div class="mw-300px">
                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack mb-3">
                                        <!--begin::Accountname-->
                                        <div class="fw-semibold pe-10 text-gray-600 fs-7">{{ trans('quotes.subtotal') }}</div>
                                        <!--end::Accountname-->

                                        <!--begin::Label-->
                                        <div class="text-end fw-bold fs-6 text-gray-800">${{ number_format($quote->price, 2) }}</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Item-->

                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack mb-3">
                                        <!--begin::Accountname-->
                                        <div class="fw-semibold pe-10 text-gray-600 fs-7">{{ trans('quotes.vat') }}</div>
                                        <!--end::Accountname-->

                                        <!--begin::Label-->
                                        <div class="text-end fw-bold fs-6 text-gray-800">0</div>
                                        <!--end::Label-->
                                    </div>
                                    <!--end::Item-->

                                    <!--begin::Item-->
                                    <div class="d-flex flex-stack">
                                        <!--begin::Code-->
                                        <div class="fw-semibold pe-10 text-gray-600 fs-7">{{ trans('quotes.total') }}</div>
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
        </div>
    </div>
</div>
<div class="card h-lg-100 min-w-md-350px">
     <!-- Other Content Unchanged -->
</div>
</div>
@push('scripts')
    <!-- Scripts Unchanged -->
@endpush
