<x-default-layout>
    <link href="{{ mix('assets/plugins/custom/fullcalendar/main.css') }}" rel="stylesheet" type="text/css" />

    @section('title')
        {{ trans('bookings.bookings') }}
    @endsection

    @section('breadcrumbs')
        {{ Breadcrumbs::render('bookings') }} <!-- Update breadcrumb -->
    @endsection

    <div id="calendar"></div>
    @push('scripts')
    <script src="{{ mix('assets/plugins/custom/fullcalendar/main.js') }}"></script>
    <script type="text/javascript">
        var bookedQuotes = @json($bookedQuotes);
        const element = document.getElementById("kt_docs_fullcalendar_basic");
        var todayDate = moment().startOf("day");
        var YM = todayDate.format("YYYY-MM");
        var YESTERDAY = todayDate.clone().subtract(1, "day").format("YYYY-MM-DD");
        var TODAY = todayDate.format("YYYY-MM-DD");
        var TOMORROW = todayDate.clone().add(1, "day").format("YYYY-MM-DD");

        var calendarEl = document.getElementById("calendar");
        var calendar = new FullCalendar.Calendar(calendarEl, {
            headerToolbar: {
                left: "prev,next today",
                center: "title",
                right: "dayGridMonth,timeGridWeek,timeGridDay,listMonth"
            },

            height: 800,
            contentHeight: 780,
            aspectRatio: 3,
            editable: false,

            nowIndicator: true,
            now: TODAY,

            views: {
                dayGridMonth: { buttonText: "{{ trans('calendar.month') }}" },
                timeGridWeek: { buttonText: "{{ trans('calendar.week') }}" },
                timeGridDay: { buttonText: "{{ trans('calendar.day') }}" }
            },

            initialView: "dayGridMonth",
            initialDate: TODAY,

            dayMaxEvents: true, // allow "more" link when too many events
            navLinks: true,
            events: bookedQuotes,

        });
        calendar.render();
    </script>
    @endpush
</x-default-layout>
