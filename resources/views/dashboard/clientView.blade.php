@extends('layouts.admin')

@section('title')
    {{ __('Dashboard')}}
@endsection

@push('css-page')
    @if($calenderTasks)
        <link rel="stylesheet" href="{{asset('assets/libs/fullcalendar/dist/fullcalendar.min.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/stylesheet-client-dashboard.css')}}">
        <link rel="stylesheet" href="{{asset('assets/css/site-client.css')}}">
    @endif
@endpush
@push('script-page')
    <script src="{{ asset('assets/js/chart.min.js') }}"></script>
    @if($calenderTasks)
        <script src="{{ asset('assets/libs/fullcalendar/dist/fullcalendar.min.js') }}"></script>
    @endif
    <script>
            @if($calenderTasks)
        var e, t, a = $('[data-toggle="event_calendar"]');
        a.length && (t = {
            header: {right: "", center: "", left: ""},
            buttonIcons: {prev: "calendar--prev", next: "calendar--next"},
            theme: !1,
            selectable: !0,
            selectHelper: !0,
            editable: false,
            events: {!! json_encode($calenderTasks) !!},
            eventStartEditable: !1,
            locale: '{{basename(App::getLocale())}}',
            viewRender: function (t) {
                e.fullCalendar("getDate").month(), $(".fullcalendar-title").html(t.title)
            },
        }, (e = a).fullCalendar(t),
            $("body").on("click", "[data-calendar-view]", function (t) {
                t.preventDefault(), $("[data-calendar-view]").removeClass("active"), $(this).addClass("active");
                var a = $(this).attr("data-calendar-view");
                e.fullCalendar("changeView", a)
            }), $("body").on("click", ".fullcalendar-btn-next", function (t) {
            t.preventDefault(), e.fullCalendar("next")
        }), $("body").on("click", ".fullcalendar-btn-prev", function (t) {
            t.preventDefault(), e.fullCalendar("prev")
        }), $("body").on("click", ".fc-today-button", function (t) {
            t.preventDefault(), e.fullCalendar("today")
        }));
        @endif

        $(document).on('click', '.fc-day-grid-event', function (e) {
            if (!$(this).hasClass('deal')) {
                e.preventDefault();
                var event = $(this);
                var title = $(this).find('.fc-content .fc-title').html();
                var size = 'md';
                var url = $(this).attr('href');
                $("#commonModal .modal-title").html(title);
                $("#commonModal .modal-dialog").addClass('modal-' + size);

                $.ajax({
                    url: url,
                    success: function (data) {
                        $('#commonModal .modal-body').html(data);
                        $("#commonModal").modal('show');
                    },
                    error: function (data) {
                        data = data.responseJSON;
                        show_toastr('Error', data.error, 'error')
                    }
                });
            }
        });



    </script>
    <script>
        var SalesChart = (function () {
            var $chart = $('#chart-sales');

            function init($this) {
                var salesChart = new Chart($this, {
                    type: 'line',
                    options: {
                        scales: {
                            yAxes: [{
                                gridLines: {
                                    color: Charts.colors.gray[200],
                                    zeroLineColor: Charts.colors.gray[200]
                                },
                            }]
                        }
                    },
                    data: {
                        labels:{!! json_encode($taskData['label']) !!},
                        datasets: {!! json_encode($taskData['dataset']) !!}
                    }
                });
                $this.data('chart', salesChart);
            };
            if ($chart.length) {
                init($chart);
            }
        })();
        var DoughnutChart = (function () {
            var $chart = $('#chart-doughnut');

            function init($this) {
                var randomScalingFactor = function () {
                    return Math.round(Math.random() * 100);
                };
                var doughnutChart = new Chart($this, {
                    type: 'doughnut',
                    data: {
                        labels: {!! json_encode($project_status) !!},
                        datasets: [{
                            data: {!! json_encode(array_values($projectData)) !!},
                            backgroundColor: ["#40c5d2", "#f36a5b", "#67b7dc"],
                            // label: 'Dataset 1'
                        }],
                    },
                    options: {
                        responsive: true,
                        legend: {
                            position: 'top',
                        },
                        animation: {
                            animateScale: true,
                            animateRotate: true
                        }
                    }
                });

                $this.data('chart', doughnutChart);

            };
            if ($chart.length) {
                init($chart);
            }
        })();
    </script>
@endpush

@section('content')
@php

  $project_task_percentage = $project['project_task_percentage'];
  $label='';
        if($project_task_percentage<=15){
            $label='bg-danger';
        }else if ($project_task_percentage > 15 && $project_task_percentage <= 33) {
            $label='bg-warning';
        } else if ($project_task_percentage > 33 && $project_task_percentage <= 70) {
            $label='bg-primary';
        } else {
            $label='bg-success';
        }


  $project_percentage = $project['project_percentage'];
  $label1='';
        if($project_percentage<=15){
            $label1='bg-danger';
        }else if ($project_percentage > 15 && $project_percentage <= 33) {
            $label1='bg-warning';
        } else if ($project_percentage > 33 && $project_percentage <= 70) {
            $label1='bg-primary';
        } else {
            $label1='bg-success';
        }

  $project_bug_percentage = $project['project_bug_percentage'];
  $label2='';
      if($project_bug_percentage<=15){
        $label2='bg-danger';
      }else if ($project_bug_percentage > 15 && $project_bug_percentage <= 33) {
        $label2='bg-warning';
      } else if ($project_bug_percentage > 33 && $project_bug_percentage <= 70) {
        $label2='bg-primary';
      } else {
        $label2='bg-success';
      }
@endphp

    <div class="row">
        @if(!empty($arrErr))
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                @if(!empty($arrErr['system']))
                    <div class="alert alert-danger text-xs">
                         {{ __('are required in') }} <a href="{{ route('settings') }}" class=""><u> {{ __('System Setting') }}</u></a>
                    </div>
                @endif
                @if(!empty($arrErr['user']))
                    <div class="alert alert-danger text-xs">
                         <a href="{{ route('users') }}" class=""><u>{{ __('here') }}</u></a>
                    </div>
                @endif
                @if(!empty($arrErr['role']))
                    <div class="alert alert-danger text-xs">
                         <a href="{{ route('roles.index') }}" class=""><u>{{ __('here') }}</u></a>
                    </div>
                @endif
            </div>
        @endif
    </div>

    <div class="row">

        @if(isset($arrCount['deal']))
            <div class=" col-sm-6">
                <div class="card card-box">
                    <div class="left-card">
                        <div class="icon-box bg-warning"><i class="fas fa-handshake"></i></div>
                        <h4>{{ __('Total Deal') }}</h4>
                    </div>
                    <div class="number-icon">{{ $arrCount['deal'] }}</div>
                    <img src="{{('assets/img/dot-icon.png')}}" class="dotted-icon-c">
                </div>
            </div>
        @endif

        @if(isset($arrCount['task']))
            <div class=" col-sm-6">
                <div class="card card-box">
                    <div class="left-card">
                        <div class="icon-box bg-danger"><i class="fas fa-tasks"></i></div>
                        <h4>{{ __('Total Deal Task') }}</h4>
                    </div>
                    <div class="number-icon">{{ $arrCount['task'] }}</div>
                    <img src="{{('assets/img/dot-icon.png')}}" class="dotted-icon-c">
                </div>
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
            <div class="card card-box height-95">
                <div class="icon-box {{$label1}}">{{ $project['projects_count'] }}</div>
                <div class="number-icon w-100">
                    <div class="card-right-title">
                        <h4 class="float-left">{{__('Total Project')}}</h4>
                        <h5 class="float-right">{{$project['project_percentage']}}%</h5>
                    </div>
                    <div class="border-progress">
                        <div class="border-inner-progress {{$label1}}" style="width:{{$project['project_percentage']}}%"></div>
                    </div>
                </div>
                <img src="{{ asset('assets/img/dot-icon.png') }}" alt="" class="dotted-icon-c">
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
            <div class="card card-box height-95">
                <div class="icon-box {{$label}}">{{$project['projects_tasks_count']}}</div>
                <div class="number-icon w-100">
                    <div class="card-right-title">
                        <h4 class="float-left">{{__('Total Project Tasks')}}</h4>
                        <h5 class="float-right">{{$project['project_task_percentage']}}%</h5>
                    </div>
                    <div class="border-progress">
                        <div class="border-inner-progress {{$label}}" style="width:{{$project['project_task_percentage']}}%"></div>
                    </div>
                </div>
                <img src="{{ asset('assets/img/dot-icon.png') }}" alt="" class="dotted-icon-c">
            </div>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-6 col-sm-6">
                <div class="card card-box height-95">
                    <div class="icon-box {{$label2}}">{{$project['projects_bugs_count']}}</div>
                    <div class="number-icon w-100">
                        <div class="card-right-title">
                            <h4 class="float-left">{{__('Total Bugs')}}</h4>
                            <h5 class="float-right">{{$project['project_bug_percentage']}}%</h5>
                        </div>
                        <div class="border-progress">
                            <div class="border-inner-progress {{$label2}}" style="width:{{$project['project_bug_percentage']}}%"></div>
                        </div>
                    </div>
                    <img src="{{ asset('assets/img/dot-icon.png') }}" alt="" class="dotted-icon-c">
                </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div>
                <h4 class="h4 font-weight-400 float-left">{{__('Tasks Overview')}}</h4>
                <h6 class="last-day-text">{{__('Last 7 Days')}}</h6>
            </div>
            <div class="card bg-none">
                <canvas id="chart-sales" height="300" class="p-3"></canvas>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="{{ (Auth::user()->type =='company' || Auth::user()->type =='client') ? 'col-xl-6 col-lg-6 col-md-6' : 'col-xl-8 col-lg-8 col-md-8' }} col-sm-12">
            <div>
                <h4 class="h4 font-weight-400 float-left">{{__('Top Due Project')}}</h4>
            </div>
            <div class="card bg-none min-410 mx-410">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th>{{__('Task Name')}}</th>
                            <th>{{__('Remain Task')}}</th>
                            <th>{{__('Due Date')}}</th>
                            <th>{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody class="list">
                        @forelse($project['projects'] as $project)
                            @php
                                $datetime1 = new DateTime($project->due_date);
                                $datetime2 = new DateTime(date('Y-m-d'));
                                $interval = $datetime1->diff($datetime2);
                                $days = $interval->format('%a');

                                 $project_last_stage = ($project->project_last_stage($project->id))?$project->project_last_stage($project->id)->id:'';
                                $total_task = $project->project_total_task($project->id);
                                $completed_task=$project->project_complete_task($project->id,$project_last_stage);
                                $remain_task=$total_task-$completed_task;
                            @endphp
                            <tr>
                                <td class="id-web">
                                    {{$project->project_name}}
                                </td>
                                <td>{{$remain_task }}</td>
                                <td>{{ Auth::user()->dateFormat($project->end_date) }}</td>
                                <td>
                                    <a href="{{ route('projects.show',$project->id) }}" class="edit-icon"><i class="fas fa-eye"></i></a>
                                </td>
                            </tr>
                        @empty
                            <tr class="text-center">
                                <td colspan="4">{{__('No Data Found.!')}}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
            <div>
                <h4 class="h4 font-weight-400 float-left">{{__('Top Due Task')}}</h4>
            </div>
            <div class="card bg-none min-410 mx-410">
                <div class="table-responsive">
                    <table class="table align-items-center mb-0">
                        <thead>
                        <tr>
                            <th>{{__('Task Name')}}</th>
                            <th>{{__('Assign To')}}</th>
                            <th>{{__('Task Stage')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($top_tasks as $top_task)
                            <tr>
                                <td class="id-web">
                                    {{$top_task->name}}
                                </td>
                                <td>
                                    <div class="avatar-group">
                                        @if($top_task->users()->count() > 0)
                                            @if($users = $top_task->users())
                                                @foreach($users as $key => $user)
                                                    @if($key<3)
                                                        <a href="#" class="avatar rounded-circle avatar-sm">
                                                            <img data-original-title="{{(!empty($user)?$user->name:'')}}" @if($user->avatar) src="{{asset('/storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('assets/img/avatar/avatar-1.png')}}" @endif title="{{ $user->name }}" class="hweb">
                                                        </a>
                                                    @else
                                                        @break
                                                    @endif
                                                @endforeach
                                            @endif
                                            @if(count($users) > 3)
                                                <a href="#" class="avatar rounded-circle avatar-sm">
                                                    <img  data-original-title="{{(!empty($user)?$user->name:'')}}" @if($user->avatar) src="{{asset('/storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('assets/img/avatar/avatar-1.png')}}" @endif class="hweb">
                                                </a>
                                            @endif
                                        @else
                                            {{ __('-') }}
                                        @endif
                                    </div>
                                </td>
                                <td><span class="badge badge-pill blue-bg">{{ $top_task->stage->name }}</span></td>
                            </tr>
                        @empty
                            <tr class="text-center">
                                <td colspan="4">{{__('No Data Found.!')}}</td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        @if($calenderTasks)
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
              <div>
                  <h4 class="h4 font-weight-400 float-left">{{__('Deal Calender')}}</h4>
              </div>
                <div class="card author-box card-primary">
                    <div class="card-header">
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
                                    <button class="fc-today-button btn btn-sm btn-neutral" type="button">{{__('Today')}}</button>
                                </div>
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="month">{{__('Month')}}</a>
                                    <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="basicWeek">{{__('Week')}}</a>
                                    <a href="#" class="btn btn-sm btn-neutral" data-calendar-view="basicDay">{{__('Day')}}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id='calendar-container'>
                            <div id='calendar' data-toggle="event_calendar"></div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                <div>
                    <h4 class="h4 font-weight-400 float-left">{{__('Project Status')}}</h4>
                </div>
                <div class="card bg-none py-4 min-410 mx-410">
                    <div class="chart">
                        <div class="chartjs-size-monitor">
                            <div class="chartjs-size-monitor-expand">
                                <div class=""></div>
                            </div>
                            <div class="chartjs-size-monitor-shrink">
                                <div class=""></div>
                            </div>
                        </div>
                        <canvas id="chart-doughnut" class="chart-canvas chartjs-render-monitor" width="734" height="350" style="display: block; width: 734px; height: 350px;"></canvas>
                    </div>
                    <div class="project-details" style="margin-top: 15px;">
                        <div class="row">
                            <div class="col text-center">
                                <div class="tx-gray-500 small">{{__('On Going')}}</div>
                                <div class="font-weight-bold">{{ number_format($projectData['on_going'],2) }}%</div>
                            </div>
                            <div class="col text-center">
                                <div class="tx-gray-500 small">{{__('On Hold')}}</div>
                                <div class="font-weight-bold">{{ number_format($projectData['on_hold'],2) }} %</div>
                            </div>
                            <div class="col text-center">
                                <div class="tx-gray-500 small">{{__('Completed')}}</div>
                                <div class="font-weight-bold">{{ number_format($projectData['completed'],2) }} %</div>
                            </div>
                            <div class="col text-center">
                                <div class="tx-gray-500 small">{{__('Canceled')}}</div>
                                <div class="font-weight-bold">{{ number_format($projectData['canceled'],2) }} %</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </div>

@endsection
