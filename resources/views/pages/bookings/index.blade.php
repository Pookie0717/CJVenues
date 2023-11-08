<x-default-layout>
    <link href="{{ mix('assets/plugins/custom/fullcalendar/main.css') }}" rel="stylesheet" type="text/css" />

    @section('title')
        Bookings
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('bookings') }} <!-- Update breadcrumb -->
    @endsection

    <div id="kt_docs_fullcalendar_populated">Calendar Coming Soon</div>

    @push('scripts')
    <script src="{{ mix('assets/plugins/custom/fullcalendar/main.js') }}"></script>
    @endpush
</x-default-layout>
