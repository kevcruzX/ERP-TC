@extends('layouts.admin')

@section('page-title')
    {{ucwords($project->project_name)}}
@endsection
@section('action-button')
    <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
        @can('edit project')
        <a href="#" data-url="{{ route('projects.edit', $project->id) }}" data-size="lg" data-ajax-popup="true"
        data-title="{{__('Edit Project')}}" class="btn btn-xs btn-white btn-icon-only width-auto">
        <i class="fas fa-pencil-alt"></i> {{__('Edit')}}</a>
        @endcan
        @can('manage bug report')
          <a href="{{ route('task.bug',$project->id) }}" class="btn btn-xs btn-white btn-icon-only width-auto">
              <i class="fas fa-bug"></i> {{__('Bug Report')}}
          </a>
        @endcan
        @can('create project task')
            <a href="{{ route('projects.tasks.index',$project->id) }}" class="btn btn-xs btn-white btn-icon-only width-auto">
                <span class="btn-inner--icon"><i class="fas fa-list"></i>{{__('Tasks')}}</span>
            </a>
        @endcan
        @if(\Auth::user()->type != 'client')
          @can('view timesheet')
              <a href="{{ route('timesheet.index',$project->id) }}" class="btn btn-xs btn-white btn-icon-only width-auto">
                  <span class="btn-inner--icon"><i class="fas fa-stopwatch"></i>{{__('Timesheet')}}</span>
              </a>
          @endcan
        @endif
        @can('view grant chart')
            <a href="{{ route('projects.gantt',$project->id) }}" class="btn btn-xs btn-white btn-icon-only width-auto">
                <span class="btn-inner--icon"><i class="fas fa-chart-pie"></i>{{__('Gantt Chart')}}</span>
            </a>
        @endcan
        @can('view expense')
            <a href="{{ route('projects.expenses.index',$project->id) }}" class="btn btn-xs btn-white btn-icon-only width-auto">
                <span class="btn-inner--icon">{{\Auth::user()->currencySymbol()}}<i class="fa fa-money"></i>{{__('Expense')}}</span>
            </a>
        @endcan
        <a href="{{ route('projects.index') }}" class="btn btn-xs btn-white btn-icon-only width-auto">
            <span class="btn-inner--icon"><i class="fas fa-arrow-left"></i>{{__('Back')}}</span>
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-xl-3 col-sm-6">
            <div class="card card-stats border-0">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-1">{{ __('Task Done')  }}</h6>

                            <span class="h4 font-weight-bold mb-0 ">{{ $project_data['task']['done'] }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="progress-circle progress-sm" data-progress="{{ $project_data['task']['percentage'] }}" data-text="{{ $project_data['task']['percentage'] }}%" data-color="primary"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <span class="text-sm text-muted">{{ __('Total Task').' : '.$project_data['task']['total'] }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="">
                        <div class="row">
                            <div class="col-12">
                                <h6 class="mb-0">{{ $project_data['task_chart']['total'] }}</h6>
                                <span class="text-sm text-muted">{{__('Last 7 days task done')}}</span>
                            </div>
                        </div>
                    </div>
                    <div class="w-100 pt-4 pb-5">
                        <div class="spark-chart" data-toggle="spark-chart" data-color="info" data-dataset="{{ json_encode($project_data['task_chart']['chart']) }}"></div>
                    </div>
                    <div class="progress-wrapper mb-3">
                        <small class="progress-label">{{ __('Day Left') }} <span class="text-muted">{{ $project_data['day_left']['day'] }}</span></small>
                        <div class="progress mt-0 height-3">
                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="{{ $project_data['day_left']['percentage'] }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $project_data['day_left']['percentage'] }}%;"></div>
                        </div>
                    </div>
                    <div class="progress-wrapper">
                        <small class="progress-label">{{__('Open Task')}} <span class="text-muted">{{ $project_data['open_task']['tasks'] }}</span></small>
                        <div class="progress mt-0 height-3">
                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="{{ $project_data['open_task']['percentage'] }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $project_data['open_task']['percentage'] }}%;"></div>
                        </div>
                    </div>
                    <div class="progress-wrapper">
                        <small class="progress-label">{{__('Completed Milestone')}} <span class="text-muted">{{ $project_data['milestone']['total'] }}</span></small>
                        <div class="progress mt-0 height-3">
                            <div class="progress-bar bg-info" role="progressbar" aria-valuenow="{{ $project_data['milestone']['percentage'] }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $project_data['milestone']['percentage'] }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-sm-6">
            <div class="card card-stats">
                <div class="card-body">
                    <div class="row">
                        <div class="col">
                            <h6 class="text-muted mb-1">{{ __('Expense') }}</h6>
                            <span class="h4 font-weight-bold mb-0 ">{{ \Auth::user()->priceFormat($project_data['expense']['total']) }}</span>
                        </div>
                        <div class="col-auto">
                            <div class="progress-circle progress-sm" data-progress="{{ $project_data['expense']['percentage'] }}" data-text="{{ $project_data['expense']['percentage'] }}%" data-color="primary"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <span class="text-sm text-muted">{{ __('Total Budget').' : '.\Auth::user()->priceFormat($project->budget) }}</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <h6 class="mb-0">{{ $project_data['timesheet_chart']['total'] }}</h6>
                            <span class="text-sm text-muted">{{__('Last 7 days hours spent')}}</span>
                        </div>
                    </div>
                    <div class="w-100 pt-4 pb-5">
                        <div class="spark-chart" data-toggle="spark-chart" data-color="warning" data-dataset="{{ json_encode($project_data['timesheet_chart']['chart']) }}"></div>
                    </div>
                    <div class="progress-wrapper mb-3">
                        <small class="progress-label">{{__('Total project time spent')}} <span class="text-muted">{{ $project_data['time_spent']['total'] }}</span></small>
                        <div class="progress mt-0 height-3">
                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="{{ $project_data['time_spent']['percentage'] }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $project_data['time_spent']['percentage'] }}%;"></div>
                        </div>
                    </div>
                    <div class="progress-wrapper">
                        <small class="progress-label">{{__('Allocated hours on task')}} <span class="text-muted">{{ $project_data['task_allocated_hrs']['hrs'] }}</span></small>
                        <div class="progress mt-0 height-3">
                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="{{ $project_data['task_allocated_hrs']['percentage'] }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $project_data['task_allocated_hrs']['percentage'] }}%;"></div>
                        </div>
                    </div>
                    <div class="progress-wrapper">
                        <small class="progress-label">{{__('User Assigned')}} <span class="text-muted">{{ $project_data['user_assigned']['total'] }}</span></small>
                        <div class="progress mt-0 height-3">
                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="{{ $project_data['user_assigned']['percentage'] }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $project_data['user_assigned']['percentage'] }}%;"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--Project Overview--}}
        <div class="card col-sm-5  col-md-6  col-lg-6  col-xl-6">
            <div class="m-1 card-fluid">
                <div class="card-header">
                    <h6 class="mb-0">{{__('Project overview')}}</h6>
                </div>
                <div class="card-body py-3 flex-grow-1">
                    <div class="pb-3 mb-3 border-bottom">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <img {{ $project->img_image }} class="avatar rounded-circle">
                            </div>
                            <div class="col ml-n2">
                                <div class="progress-wrapper">
                                    <span class="progress-percentage"><small class="font-weight-bold">{{__('Completed:')}} </small>{{ $project->project_progress()['percentage'] }}</span>
                                    <div class="progress progress-xs mt-2">
                                        <div class="progress-bar bg-{{ $project->project_progress()['color'] }}" role="progressbar" aria-valuenow="{{ $project->project_progress()['percentage'] }}" aria-valuemin="0" aria-valuemax="100" style="width: {{ $project->project_progress()['percentage'] }};"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <p class="text-sm mb-0">
                        {{ $project->description }}
                    </p>
                </div>
                <div class="card-footer py-0 px-0">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="row align-items-center">
                                <div class="col-6">
                                    <small>{{__('Start date')}}:</small>
                                    <div class="h6 mb-0">{{ \App\Utility::getDateFormated($project->start_date) }}</div>
                                </div>
                                <div class="col-6">
                                    <small>{{__('End date')}}:</small>
                                    <div class="h6 mb-0 {{ (strtotime($project->end_date) < time()) ? 'text-danger' : '' }}">{{ \App\Utility::getDateFormated($project->end_date) }}</div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        {{--Users--}}
        <div class="col-xl-6">
            <div class="card">
                <div class="card-header border-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <h6 class="mb-0">{{__('Members')}}</h6>
                        </div>
                        <div class="col-auto">

                            <div class="actions">
                                @can('edit project')
                                <a href="#" class="btn btn-sm btn-white float-right add-small" data-url="{{ route('invite.project.member.view', $project->id) }}" data-ajax-popup="true" data-size="lg" data-title="{{__('Add Member')}}">
                                    <span class="btn-inner--icon">
                                        <i class="fas fa-plus"></i>
                                        {{__('Add')}}
                                    </span>
                                </a>
                                @endcan
                            </div>

                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <div class="mh-350 min-h-350">
                        <table class="table align-items-center">
                            <tbody class="list" id="project_users">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="card col-xl-6">
            <div class="">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">{{__('Milestones')}} ({{count($project->milestones)}})</h6>
                        </div>
                        @can('create milestone')
                            <div class="text-right">
                                <a href="#" data-url="{{ route('project.milestone',$project->id) }}" data-ajax-popup="true" data-title="{{__('Create New Milestone')}}" class="btn btn-sm btn-white float-right add-small">
                                    <span class="btn-inner--icon">
                                        <i class="fas fa-plus"></i>
                                        {{__('Add')}}
                                    </span>
                                </a>
                            </div>
                        @endcan
                    </div>
                </div>

                <div class="mh-350 min-h-350">
                    <div class="list-group list-group-flush">
                        @if($project->milestones->count() > 0)
                            @foreach($project->milestones as $milestone)
                                <span class="list-group-item list-group-item-action">
                                <div class="media align-items-center">
                                    <div class="media-body">
                                        <h6 class="text-sm d-block text-limit mb-0">{{ $milestone->title }}
                                            <span class="badge badge-pill badge-{{\App\Project::$status_color[$milestone->status]}}">{{ __(\App\Project::$project_status[$milestone->status]) }}</span>
                                        </h6>
                                        <span class="d-block text-sm text-muted">{{ $milestone->tasks->count().' '. __('Tasks') }}</span>
                                    </div>
                                    <div class="media-body text-right">
                                            <a href="#" class="action-item"
                                                data-url="{{ route('project.milestone.show',$milestone->id) }}" data-ajax-popup="true"
                                                data-title="{{ $milestone->title }}" data-toggle="tooltip"
                                                data-original-title="{{ __('View') }}" data-size='lg'>
                                                <span class="btn-inner--icon"><i class="fas fa-eye"></i></span>
                                            </a>
                                        @can('edit milestone')
                                            <a href="#" class="action-item"
                                                    data-url="{{ route('project.milestone.edit',$milestone->id) }}" data-ajax-popup="true"
                                                    data-title="{{ __('Edit Milestone') }}" data-toggle="tooltip"
                                                    data-original-title="{{ __('Edit') }}" data-size='md'>
                                                    <span class="btn-inner--icon"><i class="fas fa-pencil-alt"></i></span>
                                            </a>
                                        @endcan
                                        @can('delete milestone')

                                            <a href="#" class="action-item text-danger" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?')}}|{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$milestone->id}}').submit();">
                                                <i class="fas fa-trash-alt"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['project.milestone.destroy', $milestone->id],'id'=>'delete-form-'.$milestone->id]) !!}
                                            {!! Form::close() !!}
                                        @endcan
                                    </div>
                                </div>
                            </span>
                            @endforeach
                        @else
                            <div class="py-5">
                                <h6 class="h6 text-center">{{__('No Milestone Found.')}}</h6>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">


        @can('view activity')
            <div class="col-xl-6">
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">{{__('Activity Log')}}</h6>
                                    <small>{{__('Activity Log of this project')}}</small>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="scrollbar-inner">
                            <div class="mh-500 min-h-500">
                                <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                                    @foreach($project->activities as $activity)
                                        <div class="timeline-block">
                                            <span class="timeline-step timeline-step-sm bg-dark border-dark text-white">
                                                <i class="fas {{$activity->logIcon($activity->log_type)}}"></i>
                                            </span>
                                            <div class="timeline-content">
                                                <span class="text-dark text-sm">{{ __($activity->log_type) }}</span>
                                                <a class="d-block h6 text-sm mb-0">{!! $activity->getRemark() !!}</a>
                                                <small><i class="fas fa-clock mr-1"></i>{{$activity->created_at->diffForHumans()}}</small>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endcan

        {{--Attachments--}}
        <div class="card col-xl-6">
            <div class="">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">{{__('Attachments')}}</h6>
                                <small>{{__('Attachment that uploaded in this project')}}</small>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="scrollbar-inner">
                        <div class="mh-500 min-h-500">
                            @if($project->projectAttachments()->count() > 0)
                                @foreach($project->projectAttachments() as $attachment)
                                    <div class="card mb-3 border shadow-none">
                                        <div class="px-3 py-3">
                                            <div class="row align-items-center">
                                                <div class="col-auto">
                                                    <img src="{{ asset('assets/img/icons/files/'.$attachment->extension.'.png') }}" class="img-fluid" style="width: 40px;">
                                                </div>
                                                <div class="col ml-n2">
                                                    <h6 class="text-sm mb-0">
                                                        <a href="#">{{ $attachment->name }}</a>
                                                    </h6>
                                                    <p class="card-text small text-muted">{{ $attachment->file_size }}</p>
                                                </div>
                                                <div class="col-auto actions">
                                                    <a href="{{asset(Storage::url('tasks/'.$attachment->file))}}" download class="action-item" role="button">
                                                        <i class="fas fa-download"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="py-5">
                                    <h6 class="h6 text-center">{{__('No Attachments Found.')}}</h6>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection

@push('script-page')
<script>
    $(document).ready(function () {
            loadProjectUser();
        $(document).on('click', '.invite_usr', function () {
            var project_id = $('#project_id').val();
            var user_id = $(this).attr('data-id');

            $.ajax({
                url: '{{ route('invite.project.user.member') }}',
                method: 'POST',
                dataType: 'json',
                data: {
                    'project_id': project_id,
                    'user_id': user_id,
                    "_token": "{{ csrf_token() }}"
                },
                success: function (data) {
                    if (data.code == '200') {
                        show_toastr(data.status, data.success, 'success')
                        setInterval('location.reload()', 5000);
                        loadProjectUser();
                    } else if (data.code == '404') {
                        show_toastr(data.status, data.errors, 'error')
                    }
                }
            });
        });
    });

    function loadProjectUser() {
            var mainEle = $('#project_users');
            var project_id = '{{$project->id}}';

            $.ajax({
                url: '{{ route('project.user') }}',
                data: {project_id: project_id},
                beforeSend: function () {
                    $('#project_users').html('<tr><th colspan="2" class="h6 text-center pt-5">{{__('Loading...')}}</th></tr>');
                },
                success: function (data) {
                    mainEle.html(data.html);
                    $('[id^=fire-modal]').remove();
                    loadConfirm();
                }
            });
        }
</script>
@endpush
