@extends('layouts.admin')

@section('page-title') {{__('Gantt Chart')}} @endsection

@section('action-button')
<div class="col-xs-12 col-sm-12 col-md-8 d-flex align-items-center justify-content-between justify-content-md-end">
    <div class="btn-group mr-2" id="change_view" role="group">
        <a href="{{route('projects.gantt',[$project->id,'Quarter Day'])}}" class="btn btn-xs btn-white btn-icon-only width-auto @if($duration == 'Quarter Day')active @endif" data-value="Quarter Day">{{__('Quarter Day')}}</a>
        <a href="{{route('projects.gantt',[$project->id,'Half Day'])}}" class="btn btn-xs btn-white btn-icon-only width-auto @if($duration == 'Half Day')active @endif" data-value="Half Day">{{__('Half Day')}}</a>
        <a href="{{route('projects.gantt',[$project->id,'Day'])}}" class="btn btn-xs btn-white btn-icon-only width-auto @if($duration == 'Day')active @endif" data-value="Day">{{__('Day')}}</a>
        <a href="{{route('projects.gantt',[$project->id,'Week'])}}" class="btn btn-xs btn-white btn-icon-only width-auto @if($duration == 'Week')active @endif" data-value="Week">{{__('Week')}}</a>
        <a href="{{route('projects.gantt',[$project->id,'Month'])}}" class="btn btn-xs btn-white btn-icon-only width-auto @if($duration == 'Month')active @endif" data-value="Month">{{__('Month')}}</a>
    </div>
    @can('manage project')
    <a href="{{ route('projects.show',$project->id) }}" class="btn btn-xs btn-white btn-icon-only width-auto">
        <span class="btn-inner--icon"><i class="fas fa-arrow-left"></i>{{__('Back')}}</span>
    </a>
    @endcan
</div>
@endsection

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card card-stats border-0">
                <div class="card-body"></div>
                @if($project)
                    <div class="gantt-target"></div>
                @else
                    <h1>404</h1>
                    <div class="page-description">
                        {{ __('Page Not Found') }}
                    </div>
                    <div class="page-search">
                        <p class="text-muted mt-3">{{ __("It's looking like you may have taken a wrong turn. Don't worry... it happens to the best of us. Here's a little tip that might help you get back on track.")}}</p>
                        <div class="mt-3">
                            <a class="btn-return-home badge-blue" href="{{route('home')}}"><i class="fas fa-reply"></i> {{ __('Return Home')}}</a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@if($project)
    @push('css-page')
        <link rel="stylesheet" href="{{asset('assets/css/frappe-gantt.css')}}"/>
    @endpush
    @push('script-page')
        @php
            $currantLang = basename(App::getLocale());
        @endphp
        <script>
            const month_names = {
                "{{$currantLang}}": [
                    '{{__('January')}}',
                    '{{__('February')}}',
                    '{{__('March')}}',
                    '{{__('April')}}',
                    '{{__('May')}}',
                    '{{__('June')}}',
                    '{{__('July')}}',
                    '{{__('August')}}',
                    '{{__('September')}}',
                    '{{__('October')}}',
                    '{{__('November')}}',
                    '{{__('December')}}'
                ],
                "en": [
                    'January',
                    'February',
                    'March',
                    'April',
                    'May',
                    'June',
                    'July',
                    'August',
                    'September',
                    'October',
                    'November',
                    'December'
                ],
            };
        </script>
        <script src="{{asset('assets/js/frappe-gantt.js')}}"></script>
        <script>
            var tasks = JSON.parse('{!! addslashes(json_encode($tasks)) !!}');
            var gantt_chart = new Gantt(".gantt-target", tasks, {
                custom_popup_html: function (task) {
                    return `<div class="details-container">
                                <div class="title">${task.name} <span class="badge float-right" style="background-color:${task.custom_class};color:white">${task.extra.priority}</span></div>
                                <div class="subtitle">
                                    <b>${task.progress}%</b> {{ __('Progress')}} <br>
                                    <b>${task.extra.comments}</b> {{ __('Comments')}} <br>
                                    <b>{{ __('Duration')}}</b> ${task.extra.duration}
                                </div>
                            </div>
                          `;
                },
                on_click: function (task) {
                },
                on_date_change: function (task, start, end) {
                    task_id = task.id;
                    start = moment(start);
                    end = moment(end);
                    $.ajax({
                        url: "{{route('projects.gantt.post',[$project->id])}}",
                        data: {
                            start: start.format('YYYY-MM-DD HH:mm:ss'),
                            end: end.format('YYYY-MM-DD HH:mm:ss'),
                            task_id: task_id,
                            "_token": "{{ csrf_token() }}"
                        },
                        type: 'POST',
                        success: function (data) {

                        },
                        error: function (data) {
                            show_toastr('Errors', '{{ __("Some Thing Is Wrong!")}}', 'error');
                        }
                    });
                },
                view_mode: '{{$duration}}',
                language: '{{$currantLang}}'
            });
        </script>
    @endpush
@endif
