<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ trans('quotes.title') }} #{{ $quote->quote_number }}v{{ $quote->version }}</title>
 
    <link rel="stylesheet" href="pdf/plugins.bundle.css" type="text/css">
    <link rel="stylesheet" href="assets/css/style.bundle.css" type="text/css">
    <style>
        * {
            font-family: Arial!important;
        }
    </style>
</head>

<body>
    <div class="card flex-grow-1">
        <!--begin::Body-->
        <div class="card-body p-lg-20">
            <div class="d-flex flex-stack pb-10">
                <!--begin::Logo-->
                <a href="#">
                    <img alt="Logo" style="width: 25px; height: 20px!important" src="pdf/default-dark.png" class="h-25px app-sidebar-logo-default" />
                </a>
            </div>
            <!--end::Logo-->
            <!--begin::Layout-->
            <div class="d-flex flex-column flex-xl-row">
                <!--begin::Content-->
                <div class="flex-lg-row-fluid">
                    <!--begin::Invoice 2 content-->
                    <div class="mt-n1">
                        <!--begin::Wrapper-->
                        <div class="m-0">
                            <!--begin::Label-->
                            <div class="fw-bold fs-3 text-gray-800 mb-8">{{ trans('quotes.title') }} #{{ $quote->quote_number }}v{{ $quote->version }}</div>
                            <!--end::Label-->
                            <!--begin::Row-->
                            <div class="row g-5 mb-11">
                                <!--end::Col-->
                                <table class='table'>
                                    <tr class='mb-8 fw-bold text-gray-700 fs-5'>
                                        <td>
                                            <!--end::Label-->
                                            <div class="fw-semibold fs-7 text-gray-600 mb-1">{{ trans('quotes.createdon') }}:</div>
                                            <!--end::Label-->
    
                                            <!--end::Col-->
                                            <div class="fw-bold fs-6 text-gray-800">{{ \Carbon\Carbon::parse($quote->created_at)->format('d F Y') }}</div>
                                            <!--end::Col-->
                                        </td>
                                        <td>
                                            <div class="fw-semibold fs-7 text-gray-600 mb-1">{{ trans('quotes.senton') }}:</div>
                                            <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                                <span class="pe-2">dd/mm/yyyy</span>
                                                <span class="fs-7 text-danger d-flex align-items-center">
                                                    <span class="bullet bullet-dot bg-danger me-2"></span>
                                                    {{ trans('quotes.expiring_in_days' , ['days' => "123"]) }}
                                                </span>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr class='mb-8 fw-bold text-gray-700 fs-5 pt-6'>
                                        <td >
                                            <div class="col-sm-6 pt-6">
                                                <div class="fw-semibold fs-7 text-gray-600 mb-1">{{ trans('quotes.issuedfor') }}:</div>
                                                    @foreach ($associatedContact as $contact)
                                                <div class="fw-bold fs-6 text-gray-800">{{ $contact->name }}</div>

                                                <div class="fw-semibold fs-7 text-gray-600">
                                                    {{$contact->address}} <br>
                                                    {{$contact->postcode}} {{$contact->city}} <br>
                                                    {{$contact->state}}, {{$contact->country}}
                                                </div>
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-sm-6 pt-6">
                                                <!--end::Label-->
                                                <div class="fw-semibold fs-7 text-gray-600">{{ trans('quotes.issuedby') }}:</div>
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
                                        </td>
                                    </tr>
                                    <tr class="mb-8 fw-bold text-gray-700 fs-5 pt-6">
                                        <td>
                                            <div class="col-sm-6">
                                                <!--end::Label-->
                                                <div class="fw-semibold fs-7 text-gray-600 pt-6">{{ trans('quotes.event_date') }}:</div>
                                                <div class="fw-bold fs-6 text-gray-800">
                                                    @php
                                                        $dateFromString = $quote->date_from; // Assuming this is in dd-mm-yyyy format
                                                        $dateToString = $quote->date_to; // Assuming this is also in dd-mm-yyyy format
    
                                                        $dateFrom = DateTime::createFromFormat('d-m-Y', $dateFromString);
                                                        $dateTo = DateTime::createFromFormat('d-m-Y', $dateToString);
    
                                                        $diff = $dateFrom->diff($dateTo);
    
                                                        $dateInterval = $diff->days + 1;
    
                                                        $timesFrom = explode('|', $quote->time_from);
                                                        $timesTo = explode('|', $quote->time_to);
                                                    @endphp
    
                                                    @for ($i = 0; $i < $dateInterval; $i++)
                                                        @php
                                                            $currentDate = clone $dateFrom;
                                                            $currentDate->modify("+{$i} day");
                                                            $displayDate = $currentDate->format('d-m-Y');
    
                                                            $formatTime = function($time) {
                                                                // Attempt to parse the time string with seconds
                                                                $dateTime = DateTime::createFromFormat('H:i:s', $time);
                                                                if (!$dateTime) {
                                                                    // If parsing fails, try without seconds
                                                                    $dateTime = DateTime::createFromFormat('H:i', $time);
                                                                }
                                                                // Format and return the time without seconds, default to 'Not Set' if parsing fails
                                                                return $dateTime ? $dateTime->format('H:i') : 'Not Set';
                                                            };
    
                                                            $timeFrom = $formatTime($timesFrom[$i] ?? '');
                                                            $timeTo = $formatTime($timesTo[$i] ?? '');
                                                        @endphp
    
                                                        <div>{{ $displayDate }} from {{ $timeFrom }} to {{ $timeTo }}</div>
                                                    @endfor
                                                </div>
    
                                                <div class="fw-semibold fs-7 text-gray-600 pt-3">
                                                    {{ trans('quotes.buffer_time') }} ({{$quote->buffer_time_unit}}): 
                                                    {{$quote->buffer_time_before}} {{ trans('quotes.before') }} {{ trans('quotes.and') }} {{$quote->buffer_time_after}} {{ trans('quotes.after') }}
                                                </div>
    
                                            </div>
                                        </td>
                                        <td>
                                            <div class="col-sm-6">
                                                <!--people show-->
                                                <div class="fw-semibold fs-7 text-gray-600 mb-1 pt-6">{{ trans('quotes.people') }}:</div>
                                                <!--people input-->
                                                <div class="fw-bold fs-6 text-gray-800 show-mode">{{ $quote->people }}</div>
                                                <!--end people show-->
                                                <div class="fw-semibold fs-7 text-gray-600 pt-6">{{ trans('quotes.event_kind') }}:</div>
                                                <div class="fw-bold fs-6 text-gray-800">{{ $quote->event_name ? $quote->event_name : 'N/A' }} </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <table class="table">
                                <thead>
                                    <tr class="border-bottom fs-6 fw-bold text-muted">
                                        <th class="min-w-175px pb-2">{{ trans('quotes.details') }}</th>
                                    </tr>
                                </thead>
                                <div style='width: 100%;border-bottom:1px solid #b7b7b7;padding-bottom: 15px'></div>
                                <tbody>
                                    <tr class="fw-bold text-gray-700 fs-5 show-mode">
                                        <td  class="text-left pt-6 " style="font-size: 80%;">
                                            {{ $details }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <table class="table" style="padding-top: 30px;width: 100%">
                                <thead>
                                    <tr class="border-bottom fs-6 fw-bold text-muted">
                                        <th class="min-w-175px pb-2" style="border-bottom:1px solid #b7b7b7;">{{ trans('quotes.description') }}</th>
                                        <th class="min-w-100px text-end pb-2" style="border-bottom:1px solid #b7b7b7;">{{ trans('quotes.quantity') }}</th>
                                        <th class="min-w-100px text-end pb-2" style="border-bottom:1px solid #b7b7b7;">{{ trans('quotes.unit') }}</th>
                                        <th class="min-w-100px text-end pb-2" style="border-bottom:1px solid #b7b7b7;">{{ trans('quotes.price') }}</th>
                                    </tr>
                                </thead>
                                <tbody id="parentTable">
                                    @if($quote->price_venue != 0)
                                    <tr class="fw-bold text-gray-700 fs-5 show-mode">
                                        <td class="d-flex align-items-center text-left pt-6 align-middle">
                                            {{($quote->eventArea ? $quote->eventArea->name : 'N/A')}}
                                        </td>

                                        <td class="pt-6 text-end align-middle">{{$quote->venue_count}}</td>
                                        <td class="pt-6 text-end align-middle">$ {{ number_format($quote->price_venue, 2) }}</td>
                                        <td class="pt-6 text-dark fw-bolder text-end align-middle">$ {{ number_format($quote->price_venue, 2) }}</td>
                                    </tr>
                                    <div style='width: 100%;border-bottom:1px solid ##e3e2e2;padding-bottom: 15px'></div>
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
                                                    @if($optionWithValue['type'] == 'yes_no' && $optionWithValue['value'] == 'yes' && $quote->options_name)
                                                        {{ $optionWithValue['option']->name }}
                                                    @elseif($optionWithValue['type'] == 'yes_no' && $optionWithValue['value'] == 'yes')
                                                        {{ $optionWithValue['option']->name }}
                                                    @elseif($quote->options_name)
                                                        {{ $optionWithValue['option']->name }}
                                                    @else
                                                        {{ $optionWithValue['option']->name }}
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
                                            <div style='width: 100%;border-bottom:1px solid ##e3e2e2;padding-bottom: 15px'></div>
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
                                            <div style='width: 100%;border-bottom:1px solid ##e3e2e2;padding-bottom: 15px'></div>
                                        @endif
                                    @endforeach
                                    @if($quote->extra_items_count)
                                        @foreach($extraItemsName as $index => $extraItemName)
                                            <tr class="fw-bold text-gray-700 fs-5 show-mode extra-items">
                                                <td class="d-flex align-items-center text-left pt-6 align-middle">
                                                    {{ $extraItemName }}
                                                </td>
                                                <td class="pt-6 text-end align-middle">{{ $extraItemsCount[$index] }}</td>
                                                <td class="pt-6 text-end align-middle">${{number_format($extraItemsPrice[$index] / $extraItemsCount[$index], 2)}}</td>
                                                <td class="pt-6 text-dark fw-bolder text-end align-middle">${{ number_format($extraItemsPrice[$index], 2)}}</td>
                                            </tr>
                                            <div style='width: 100%;border-bottom:1px solid ##e3e2e2;padding-bottom: 15px'></div>
                                        @endforeach
                                    @endif
                                    <tr class="fw-bold text-gray-700 fs-5 show-mode" style='border-top:1px solid #b7b7b7'>
                                        <td></td>
                                        <td></td>
                                        <td class='pt-12 text-end align-middle'><span>{{ trans('quotes.subtotal') }}:</span></td>
                                        <td class='pt-12 text-end align-middle'>
                                            <span class='text-end'>$ {{ number_format($quote->calculated_price, 2) }}</span>
                                        </td>
                                    </tr>
                                    <tr class="fw-bold text-gray-700 fs-5 show-mode">
                                        <td></td>
                                        <td></td>
                                        <td class='pt-6 text-end align-middle'>
                                            @if($discount)
                                                <span>{{ trans('quotes.discount') }}:</span>
                                            @endif
                                                <span>{{ trans('quotes.vat') }}:</span>
                                        </td>
                                        <td class='pt-6 text-end align-middle'>
                                            @if($discount)
                                                <span>{{ trans('quotes.discount') }}:</span>
                                            @endif
                                                <span class='text-end'> 0</span>
                                        </td>
                                    </tr>
                                    <tr class="fw-bold text-gray-700 fs-5 show-mode">
                                        <td></td>
                                        <td></td>
                                        <td class='pt-6 text-end align-middle'>
                                            <span>{{ trans('quotes.total') }}:</span> 
                                        </td>
                                        <td class='pt-6 text-end align-middle'>
                                           <span >$ {{ number_format($quote->price, 2) }}</span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>