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
                            <div>
                                <button class="btn btn-sm btn-success" id="book-quote" data-quote-id="{{ $quote->id }}">
                                    {{ trans('quotes.book') }}
                                </button>
                                <button class="btn btn-sm btn-primary show-mode" id="edit-quote" data-quote-id="{{ $quote->id }}">
                                    {{ trans('quotes.edit') }}
                                </button>
                                <button style="display:none" class="btn btn-sm btn-primary edit-mode" id="submit-quote" data-quote-id="{{ $quote->id }}" >
                                    {{ trans('quotes.submit') }}
                                </button>
                            </div>
                            <!--end::Action-->
                            
                        </div>
                        <!--end::Top-->

                        <!--begin::Wrapper-->
                        <div class="m-0">
                            <!--begin::Label-->
                            <div class="fw-bold fs-3 text-gray-800 mb-8">{{ trans('quotes.title') }} #{{ $quote->quote_number }}v{{ $quote->version }}</div>
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

                            <!--begin::Row-->
                            <div class="row g-5 mb-12">
                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--end::Label-->
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">{{ trans('quotes.event_date') }}:</div>
                                    <!--end::Label-->

                                    <!--end::Text-->
                                    <div class="fw-bold fs-6 text-gray-800">
                                        {{ $quote->date_from }}
                                        @if($quote->date_from != $quote->date_to)
                                            - {{ $quote->date_to }}
                                        @endif
                                        <br>
                                        {{$quote->time_from}} - {{$quote->time_to}}
                                    </div>
                                    <!--end::Text-->

                                    <!--start::Description-->
                                    <div class="fw-semibold fs-7 text-gray-600">
                                        {{ trans('quotes.buffer_time') }} ({{$quote->buffer_time_unit}}): {{$quote->buffer_time_before}} {{ trans('quotes.before') }} {{ trans('quotes.and') }} {{$quote->buffer_time_after}} {{ trans('quotes.after') }}
                                    </div>
                                    <!--end::Description-->
                                </div>
                                <!--end::Col-->

                                <!--end::Col-->
                                <div class="col-sm-6">
                                    <!--people show-->
                                    <div class="fw-semibold fs-7 text-gray-600 mb-1">{{ trans('quotes.people') }}:</div>
                                    <!--people input-->
                                    <input style="display:none" type="text" id="people_count" class="form-control form-control-solid edit-mode" value="{{ $quote->people }}"/>
                                    <div class="fw-bold fs-6 text-gray-800 show-mode">{{ $quote->people }}</div>
                                    <!--end people show-->

                                    <div class="fw-semibold fs-7 text-gray-600">{{ trans('quotes.event_kind') }}:</div>
                                    <div class="fw-bold fs-6 text-gray-800">{{ $quote->event_name ? $quote->event_name : 'N/A' }} </div>
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
                                                <th colspan="4" class="min-w-175px pb-2">{{ trans('quotes.details') }}</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <tr style="display:none" class="fw-bold text-gray-700 fs-5 edit-mode">
                                                <td colspan="4" class="text-left pt-6" style="font-size: 80%;">
                                                    <textarea id="details" type="text" class="form-control form-control-solid" rows="5" cols="30" style="resize: none">{{$details}}</textarea>
                                                </td>
                                            </tr>
                                            <tr class="fw-bold text-gray-700 fs-5 show-mode">
                                                <td colspan="4" class="text-left pt-6 " style="font-size: 80%;">
                                                    {{ $details }}
                                                </td>
                                            </tr>
                                        </tbody>
                                        <thead>
                                            <tr class="border-bottom fs-6 fw-bold text-muted">
                                                <th class="min-w-175px pb-2">{{ trans('quotes.description') }}</th>
                                                <th class="min-w-100px text-end pb-2">{{ trans('quotes.quantity') }}</th>
                                                <th class="min-w-100px text-end pb-2">{{ trans('quotes.unit') }}</th>
                                                <th class="min-w-100px text-end pb-2">{{ trans('quotes.price') }}</th>
                                                <th class="min-w-130px text-end pb-2 edit-mode" style="display:none">{{ trans('quotes.action') }}</th>
                                                <th class="min-w-0px text-end pb-2 show-mode" ></th>
                                            </tr>
                                        </thead>
                                        <tbody id="parentTable">
                                            <tr class="fw-bold text-gray-700 fs-5 show-mode" style="display:none"></tr>
                                            <tr class="fw-bold text-gray-700 fs-5 edit-mode" style="display:none">
                                                <td class="d-flex align-items-center text-left pt-6 align-middle">
                                                    <button type="button" class="btn btn-primary btn-sm" onclick="add_item()">Add Item</button>
                                                </td>
                                            </tr>
                                            @if($quote->price_venue != 0)
                                            <tr class="fw-bold text-gray-700 fs-5 show-mode">
                                                <td class="d-flex align-items-center text-left pt-6 align-middle">
                                                    <i class="fa fa-genderless text-danger fs-2 me-2"></i>
                                                    {{$quote->venues_name ? $quote->venues_name : ($quote->eventType ? $quote->eventType->event_name : 'N/A').' - '.($quote->eventArea ? $quote->eventArea->name : 'N/A')}}
                                                </td>

                                                <td class="pt-6 text-end align-middle">{{$quote->venue_count}}</td>
                                                <td class="pt-6 text-end align-middle">$ {{ number_format($quote->price_venue, 2) }}</td>
                                                <td class="pt-6 text-dark fw-bolder text-end align-middle">$ {{ number_format($quote->price_venue, 2) }}</td>
                                            </tr>
                                            <tr class="fw-bold text-gray-700 fs-5 edit-mode venue-items" style="display:none">
                                                <td class="d-flex align-items-center text-left pt-6 align-middle">
                                                    <i class="fa fa-genderless text-danger fs-2 me-2"></i>
                                                    <input style='width:200px' type="text" class="form-control form-control-solid" value="{{$quote->venues_name ? $quote->venues_name : ($quote->eventType ? $quote->eventType->event_name : 'N/A').' - '.($quote->eventArea ? $quote->eventArea->name : 'N/A')}}" />
                                                </td>
                                                <td class="pt-6 text-end align-middle">
                                                    <input style='width:100px;display:inline-block;text-align:right' type="text" class="form-control form-control-solid" value="1" />
                                                </td>
                                                <td class="pt-6 text-end align-middle">
                                                    <input style='width:100px;display:inline-block;text-align:right' type="text" class="form-control form-control-solid" value="{{ $quote->price_venue }}" />
                                                </td>
                                                <td class="pt-6 text-dark fw-bolder text-end align-middle">
                                                    {{ $quote->price_venue }}
                                                    <!-- <input style='width:100px;display:inline-block;text-align:right' type="text" class="form-control form-control-solid" value="{{  $quote->price_venue }}" /> -->
                                                </td>
                                                <td class="d-flex justify-content-end align-items-center pt-6 align-middle">
                                                    <button type="button" class="btn btn-danger btn-sm text-right" onclick="remove_item(event)">Remove</button>
                                                </td>
                                            </tr>
                                            @endif
                                            <!-- Additional row for options and priceOption -->
                                            @php
                                                // Convert the price_options string into an array
                                                $priceOptionsArray = explode('|', $quote->price_options);
                                            @endphp
                                            @foreach($optionsWithValues as $index => $optionWithValue)
                                                @if(!($optionWithValue['type'] == 'yes_no' && $optionWithValue['value'] == 'no'))
                                                @php
                                                    $price = floatval($priceOptionsArray[$index]);
                                                    if($optionWithValue['type'] == 'radio' || $optionWithValue['type'] == 'always' || $optionWithValue['value'] == 'yes') {
                                                        $value = 1;
                                                    } else {
                                                        $value = floatval($optionWithValue['value']);
                                                    }
                                                    if($quote->options_count) {
                                                        $value = explode('|', $quote->options_count)[$index];
                                                    }
                                                @endphp
                                                    <tr class="fw-bold text-gray-700 fs-5 show-mode">
                                                        <td class="d-flex align-items-center text-left pt-6 align-middle">
                                                            <i class="fa fa-genderless text-danger fs-2 me-2"></i>
                                                            @if($optionWithValue['type'] == 'yes_no' && $optionWithValue['value'] == 'yes' && $quote->options_name)
                                                                {{ $optionWithValue['option']->name }}
                                                            @elseif($optionWithValue['type'] == 'yes_no' && $optionWithValue['value'] == 'yes')
                                                                {{ $quote->eventType ? $quote->eventType->event_name : 'N/A' }} - {{ $optionWithValue['option']->name }}
                                                            @elseif($quote->options_name)
                                                                {{ $optionWithValue['option']->name }}
                                                            @else
                                                                {{ $quote->eventType ? $quote->eventType->event_name : 'N/A' }} - {{ $optionWithValue['option']->name }}
                                                            @endif
                                                        </td>
                                                        <td class="pt-6 text-end align-middle">{{ $value }}</td>
                                                        <td class="pt-6 text-end align-middle">
                                                            @if($value != 0)
                                                                ${{ number_format((float) $priceOptionsArray[$index] / (float) $value, 2) }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td class="pt-6 text-dark fw-bolder text-end align-middle">
                                                            $ {{ isset($priceOptionsArray[$index]) ? number_format((float) $priceOptionsArray[$index], 2) : 'N/A' }}
                                                        </td>
                                                    </tr>
                                                    <tr class="fw-bold text-gray-700 fs-5 option-items edit-mode" style="display:none" data-option-id="{{$optionWithValue['option']['id']}}" data-option-value="{{$optionWithValue['value']}}">
                                                        <td class="d-flex align-items-center text-left pt-6 align-middle">
                                                            <i class="fa fa-genderless text-danger fs-2 me-2"></i>
                                                            <input style='width:200px' type="text" class="form-control form-control-solid" value="{{
                                                                $optionWithValue['type'] == 'yes_no' && $optionWithValue['value'] == 'yes' && $quote->options_name
                                                                    ? $optionWithValue['option']->name
                                                                    : (
                                                                        $optionWithValue['type'] == 'yes_no' && $optionWithValue['value'] == 'yes'
                                                                        ? ($quote->eventType ? $quote->eventType->event_name : 'N/A') . ' - ' . $optionWithValue['option']->name
                                                                        : (
                                                                            $quote->options_name
                                                                            ? $optionWithValue['option']->name
                                                                            : ($quote->eventType ? $quote->eventType->event_name : 'N/A') . ' - ' . $optionWithValue['option']->name
                                                                        )
                                                                    )
                                                            }}" />
                                                        </td>
                                                        <td class="pt-6 text-end align-middle">
                                                            <input style='width:100px;display:inline-block;text-align:right' type="text" class="form-control form-control-solid" value="{{ $value }}" />
                                                        </td>
                                                        <td class="pt-6 text-end align-middle">
                                                            <input style='width:100px;display:inline-block;text-align:right' type="text" class="form-control form-control-solid" value="{{ $value != 0 ?  (float) $priceOptionsArray[$index] / (float) $value : N/A}}" />
                                                        </td>
                                                        <td class="pt-6 text-dark fw-bolder text-end align-middle">
                                                            <!-- <input style='width:100px;display:inline-block;text-align:right' type="text" class="form-control form-control-solid" value="{{ isset($priceOptionsArray[$index]) ? (float) $priceOptionsArray[$index] : 'N/A' }}" /> -->
                                                            {{ isset($priceOptionsArray[$index]) ? (float) $priceOptionsArray[$index] : 'N/A' }}
                                                        </td>
                                                        <td class="d-flex justify-content-end align-items-center pt-6 align-middle">
                                                            <button type="button" class="btn btn-danger btn-sm text-right" onclick="remove_item(event)">Remove</button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            @php
                                                $value = 1;
                                                $staffTypes = [
                                                    'waiter' => ['data' => $waiter, 'price' => $waiterPrice],
                                                    'venueManager' => ['data' => $venueManagers, 'price' => $venueManagersPrice],
                                                    'toiletStaff' => ['data' => $toiletStaffs, 'price' => $toiletStaffsPrice],
                                                    'cleaner' => ['data' => $cleaners, 'price' => $cleanersPrice],
                                                    'barStaff' => ['data' => $barStaff, 'price' => $barStaffPrice],
                                                    'other' => ['data' => $other, 'price' => $otherPrice],
                                                ];
                                            @endphp
                                            @foreach ($staffTypes as $staffType => $staffData)
                                                @if (count($staffData['data']) !== 0)
                                                    <tr class="fw-bold text-gray-700 fs-5 show-mode" >
                                                        <td class="d-flex align-items-center text-left pt-6 align-middle">
                                                            <i class="fa fa-genderless text-danger fs-2 me-2"></i>
                                                            {{ $staffData['data'][0]['name'] }}
                                                        </td>
                                                        <td class="pt-6 text-end align-middle">{{ $staffData['data'][0]['quantity'] }}</td>
                                                        <td class="pt-6 text-end align-middle">
                                                            @if($staffData['data'][0]['quantity'] != 0)
                                                                ${{ number_format((float) $staffData['price'] / (float) $staffData['data'][0]['quantity'], 2) }}
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td class="pt-6 text-dark fw-bolder text-end align-middle">
                                                            $ {{ isset($staffData['price']) ? number_format((float) $staffData['price'], 2) : 'N/A' }}
                                                        </td>
                                                    </tr>
                                                    <tr class="fw-bold text-gray-700 fs-5 edit-mode staff-items" style="display:none" data-staff-id="{{ $staffData['data'][0]['id'] }}">
                                                        <td class="d-flex align-items-center text-left pt-6 align-middle">
                                                            <i class="fa fa-genderless text-danger fs-2 me-2"></i>
                                                            <input style='width:200px;display:inline-block' type="text" class="form-control form-control-solid" value="{{ $staffData['data'][0]['name'] }}" />
                                                        </td>
                                                        <td class="pt-6 text-end align-middle">
                                                            <input style='width:100px;display:inline-block;text-align:right' type="text" class="form-control form-control-solid" value="{{ $staffData['data'][0]['quantity'] }}" />
                                                        </td>
                                                        <td class="pt-6 text-end align-middle">
                                                            <input style='width:100px;display:inline-block;text-align:right' type="text" class="form-control form-control-solid" value="{{ (float) $staffData['price'] / (float) $staffData['data'][0]['quantity']}}" />
                                                        </td>
                                                        <td class="pt-6 text-dark fw-bolder text-end align-middle">
                                                            ${{ isset($staffData['price']) ? number_format((float) $staffData['price'], 2) : 'N/A' }}
                                                        </td>
                                                        <td class="d-flex justify-content-end align-items-center pt-6 align-middle">
                                                            <button type="button" class="btn btn-danger btn-sm text-right" onclick="remove_item(event)">Remove</button>
                                                        </td>
                                                    </tr>
                                                @endif
                                            @endforeach
                                            @if($quote->extra_items_count)
                                                @foreach($extraItemsName as $index => $extraItemName)
                                                    <tr class="fw-bold text-gray-700 fs-5 show-mode extra-items">
                                                        <td class="d-flex align-items-center text-left pt-6 align-middle">
                                                            <i class="fa fa-genderless text-danger fs-2 me-2"></i>
                                                            {{ $extraItemName }}
                                                        </td>
                                                        <td class="pt-6 text-end align-middle">{{ $extraItemsCount[$index] }}</td>
                                                        <td class="pt-6 text-end align-middle">${{$extraItemsPrice[$index] / $extraItemsCount[$index]}}</td>
                                                        <td class="pt-6 text-dark fw-bolder text-end align-middle">${{$extraItemsPrice[$index]}}</td>
                                                    </tr>
                                                    <tr class="fw-bold text-gray-700 fs-5 edit-mode extra-items" style="display:none">
                                                        <td class="d-flex align-items-center text-left pt-6 align-middle">
                                                            <i class="fa fa-genderless text-danger fs-2 me-2"></i>
                                                            <input style='width:200px' type="text" class="form-control form-control-solid" value="{{$extraItemName}}" />
                                                        </td>
                                                        <td class="pt-6 text-end align-middle">
                                                            <input style='width:100px;display:inline-block;text-align:right' type="text" class="form-control form-control-solid" value="{{ $extraItemsCount[$index] }}" />
                                                        </td>
                                                        <td class="pt-6 text-end align-middle">
                                                            <input style='width:100px;display:inline-block;text-align:right' type="text" class="form-control form-control-solid" value="{{$extraItemsPrice[$index] / $extraItemsCount[$index]}}" />
                                                        </td>
                                                        <td class="pt-6 text-dark fw-bolder text-end align-middle">{{$extraItemsPrice[$index]}}</td>
                                                        <td class="d-flex justify-content-end align-items-center pt-6 align-middle">
                                                            <button type="button" class="btn btn-danger btn-sm text-right" onclick="remove_item(event)">Remove</button>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
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
    <div class="card h-lg-100 min-w-md-400px">
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
                                                        {{ $relatedQuotes[count($relatedQuotes) - 1]->created_at->format('d/m/Y H:i') }}
                                                    @else
                                                        {{ $relatedQuotes[$key - 1]->created_at->format('d-m-Y') }}
                                                    @endif
                                                </td>
                                                <td class="pt-6 text-dark fw-bolder">
                                                    <a href="{{ route('quotes.show', $quoteHistory) }}">{{ trans('general.view') }}</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                            <tr>
                                                
                                            </tr>
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
            const eidtButton = document.getElementById('edit-quote');
            const submitButton = document.getElementById('submit-quote');

            if(eidtButton) {
                eidtButton.addEventListener('click', function() {
                    var editElements = document.getElementsByClassName("edit-mode");
                    console.log(editElements);
                    var showElements = document.getElementsByClassName("show-mode");
                    for (var i = 0; i < editElements.length; i++) {
                        editElements[i].style.display = ""; 
                        showElements[i].style.display = "none"; 
                    }
                });
            }

            if(submitButton) {
                submitButton.addEventListener('click', function() {
                    var editElements = document.getElementsByClassName("edit-mode");
                    var showElements = document.getElementsByClassName("show-mode");
                    for (var i = 0; i < editElements.length; i++) {
                        editElements[i].style.display = "none"; 
                        showElements[i].style.display = ""; 
                    }
                    //people, details
                    var updatedData = {};
                    updatedData['people'] = document.getElementById('people_count').value;
                    updatedData['details'] = document.getElementById('details').value;
                    //options, venues, staffs
                    var optionElements = document.getElementsByClassName("option-items");
                    var venueElements = document.getElementsByClassName("venue-items");
                    var staffElements = document.getElementsByClassName("staff-items");
                    var extraElements = document.getElementsByClassName("extra-items");
                    updatedData['options'] = {};
                    console.log(optionElements);
                    for(var i = 0;i < optionElements.length;i++) {
                        updatedData['options'][i] = {};
                        for (var j = 0; j < optionElements[i].children.length; j++) {
                            if(optionElements[i].children[j].querySelector('input')) {
                                updatedData['options'][i][j] = optionElements[i].children[j].querySelector('input').value;
                            }
                        }
                        updatedData['options'][i]['id'] = optionElements[i].getAttribute('data-option-id');
                        updatedData['options'][i]['value'] = optionElements[i].getAttribute('data-option-value');
                    }
                    updatedData['venues'] = {};
                    for(var i = 0;i < venueElements.length;i++) {
                        updatedData['venues'][i] = {};
                        for (var j = 0; j < venueElements[i].children.length; j++) {
                            if(venueElements[i].children[j].querySelector('input')) { 
                                updatedData['venues'][i][j] = venueElements[i].children[j].querySelector('input').value;
                            }
                        }
                    }
                    updatedData['staffs'] = {};
                    for(var i = 0;i < staffElements.length;i++) {
                        updatedData['staffs'][i] = {};
                        for (var j = 0; j < staffElements[i].children.length; j++) {
                            if(staffElements[i].children[j].querySelector('input')) { 
                                updatedData['staffs'][i][j] = staffElements[i].children[j].querySelector('input').value;
                            }
                        }
                        updatedData['staffs'][i]['id'] = staffElements[i].getAttribute('data-staff-id');
                    }
                    updatedData['extra'] = {};
                    for(var i = 0;i < extraElements.length;i++) {
                        updatedData['extra'][i] = {};
                        for (var j = 0; j < extraElements[i].children.length; j++) {
                            if(extraElements[i].children[j].querySelector('input')) { 
                                updatedData['extra'][i][j] = extraElements[i].children[j].querySelector('input').value;
                            }
                        }
                    }
                    updatedData['quoteId'] = document.getElementById('submit-quote').getAttribute('data-quote-id');
                    const quoteId = submitButton.getAttribute('data-quote-id');
                    axios.post(`/quotes/${quoteId}/update`, updatedData, {
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json',
                        },
                    })
                    .then(function (response) {
                        console.log(response.data);
                        location.reload();
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
                });
            }

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

        function add_item() {
            // Create the row
            var newRow = document.createElement('tr');
            var newRow1 = document.createElement('tr');
            newRow1.className = "fw-bold text-gray-700 fs-5 show-mode extra-items";
            newRow.className = "fw-bold text-gray-700 fs-5 edit-mode extra-items";
            newRow.style.display = '';
            // Create the first cell and its children
            var newTd1 = document.createElement('td');
            newTd1.className = "d-flex align-items-center text-left pt-6 align-middle";

            var newTd2 = document.createElement('td');
            newTd2.className = "pt-6 text-end align-middle";

            var newTd3 = document.createElement('td');
            newTd3.className = "pt-6 text-end align-middle";

            var newTd4 = document.createElement('td');
            newTd4.className = "pt-6 text-dark fw-bolder text-end align-middle";
            
            var newItag = document.createElement('i');
            newItag.className = "fa fa-genderless text-danger fs-2 me-2";

            var newInput1 = document.createElement('input');
            newInput1.className = 'form-control form-control-solid';
            newInput1.style.width = '200px';
            newInput1.style.display = 'inline-block';
            newInput1.style.textAlign = 'right';
            newInput1.type = 'text';

            var newInput2 = document.createElement('input');
            newInput2.className = 'form-control form-control-solid';
            newInput2.style.width = '100px';
            newInput2.style.display = 'inline-block';
            newInput2.style.textAlign = 'right';
            newInput2.type = 'text';

            var newInput3 = document.createElement('input');
            newInput3.className = 'form-control form-control-solid';
            newInput3.style.width = '100px';
            newInput3.style.display = 'inline-block';
            newInput3.style.textAlign = 'right';
            newInput3.type = 'text';

            // Set the value of newInput1 based on your logic or leave it as an exercise
            
            newTd1.appendChild(newItag);
            newTd1.appendChild(newInput1);
            newTd2.appendChild(newInput2);
            newTd3.appendChild(newInput3);
            
            // Create the Remove button cell
            var newTdRemove = document.createElement('td');
            newTdRemove.className = "d-flex justify-content-end align-items-center pt-6 align-middle";
            
            var removeButton = document.createElement('button');
            removeButton.type = "button";
            removeButton.className = "btn btn-danger btn-sm text-right";
            removeButton.textContent = "Remove";
            removeButton.onclick = function(event) {
                remove_item(event);
            };
            
            newTdRemove.appendChild(removeButton);
            
            // Append all cells to the row
            newRow.appendChild(newTd1);
            newRow.appendChild(newTd2);
            newRow.appendChild(newTd3);
            newRow.appendChild(newTd4);
            // Append other cells similarly
            newRow.appendChild(newTdRemove);
            
            // Append the row to the table
            document.getElementById('parentTable').appendChild(newRow1);
            document.getElementById('parentTable').appendChild(newRow);
        }

        // Example remove_item function
        function remove_item(event) {
            event.target.closest('tr').remove();
        }

        function remove_item(event) {
            console.log(event);
            // Prevent the default button action
            event.preventDefault();
            
            // 'event.target' refers to the button that was clicked
            var buttonClicked = event.target;
            
            // Find the closest parent <tr> element
            var parentRow = buttonClicked.closest('tr');
            
            // Remove the <tr> element
            if (parentRow) {
                parentRow.remove();
            }
        }
    </script>
    @endpush
</x-default-layout>