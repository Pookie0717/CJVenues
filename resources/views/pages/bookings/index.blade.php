<x-default-layout>
    <link href="{{ mix('assets/plugins/custom/fullcalendar/main.css') }}" rel="stylesheet" type="text/css" />

    @section('title')
        Bookings
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('bookings') }} <!-- Update breadcrumb -->
    @endsection

    <div id="calendar"></div>

    @push('scripts')
    <script src="{{ mix('assets/plugins/custom/fullcalendar/main.js') }}"></script>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
          initialView: 'dayGridMonth'
        });
        calendar.render();
      });
    </script>
    @endpush
</x-default-layout>
