<x-default-layout>

    @section('title')
        Bookings
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('bookings') }}  <!-- Update breadcrumb -->
    @endsection

    <div id="kt_docs_fullcalendar_populated">Calendar Coming Soon</div>

    @push('scripts')
        
    @endpush

</x-default-layout>
