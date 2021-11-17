@extends('layouts.admin')

@section('page-title')
    {{__('Task Calendar')}}
@endsection
@push('css-page')
    <link rel="stylesheet" href="{{ asset('assets/libs/fullcalendar/dist/fullcalendar.min.css') }}">
@endpush
@push('theme-script')
    <script src="{{ asset('assets/libs/fullcalendar/dist/fullcalendar.min.js') }}"></script>
@endpush

@section('content')
<div class="page-title">
    <div class="row justify-content-between align-items-center full-calender">
        <div class="col d-flex align-items-center">
            <div class="btn-group" role="group" aria-label="Basic example">
                <a href="#" class="fullcalendar-btn-prev btn btn-sm btn-neutral">
                    <i class="fas fa-angle-left"></i>
                </a>
                <a href="#" class="fullcalendar-btn-next btn btn-sm btn-neutral">
                    <i class="fas fa-angle-right"></i>
                </a>
            </div>
            <h5 class="fullcalendar-title h4 d-inline-block font-weight-400 mb-0"></h5>
        </div>
        <div class="col-lg-6 mt-3 mt-lg-0 text-lg-right">
            <div class="btn-group" role="group" aria-label="Basic example">
                <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="month">{{__('Month')}}</a>
                <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="basicWeek">{{__('Week')}}</a>
                <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="basicDay">{{__('Day')}}</a>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <!-- Fullcalendar -->
        <div class="card overflow-hidden widget-calendar">
            <div class="calendar e-height" data-toggle="event_calendar" id="event_calendar"></div>
        </div>

    </div>
</div>
@endsection


@push('script-page')
    <script>
    var e, t, a = $('[data-toggle="event_calendar"]');
       a.length && (t = {
           header: {right: "", center: "", left: ""},
           buttonIcons: {prev: "calendar--prev", next: "calendar--next"},
           theme: !1,
           selectable: !0,
           selectHelper: !0,
           editable: !0,
           events:  {!! json_encode($arrTasks) !!},
           eventStartEditable: !1,
           locale: '{{basename(App::getLocale())}}',

           eventResize: function (event) {
               var eventObj = {
                   start: event.start.format(),
                   end: event.end.format(),
               };

               $.ajax({
                   url: event.resize_url,
                   method: 'PUT',
                   data: eventObj,
                   success: function (response) {

                   },
                   error: function (data) {
                       data = data.responseJSON;
                   }
               });
           },
           viewRender: function (t) {

               e.fullCalendar("getDate").month(), $(".fullcalendar-title").html(t.title)
           },
           eventClick: function (e, t) {
               var title = e.title;
               var url = e.url;


               if (typeof url != 'undefined') {
                   $("#commonModal .modal-title").html(title);
                   $("#commonModal .modal-dialog").addClass('modal-md');
                   $("#commonModal").modal('show');
                   $.get(url, {}, function (data) {

                       $('#commonModal .modal-body').html(data);
                   });
                   return false;
               }
           }
       }, (e = a).fullCalendar(t),
           $("body").on("click", "[data-calendar-view]", function (t) {
               t.preventDefault(), $("[data-calendar-view]").removeClass("active"), $(this).addClass("active");
               var a = $(this).attr("data-calendar-view");
               e.fullCalendar("changeView", a)
           }), $("body").on("click", ".fullcalendar-btn-next", function (t) {
           t.preventDefault(), e.fullCalendar("next")
       }), $("body").on("click", ".fullcalendar-btn-prev", function (t) {
           t.preventDefault(), e.fullCalendar("prev")
       }));



    </script>
@endpush
