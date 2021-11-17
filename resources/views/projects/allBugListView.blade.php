{{$view}}
@extends('layouts.admin')
@section('page-title')
    {{__('Manage Bug Report')}}
@endsection

@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
      <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
        @if($view == 'grid')
            <a href="{{ route('bugs.view', 'list') }}" class="btn btn-xs btn-white btn-icon-only width-auto">
                <span class="btn-inner--text"><i class="fas fa-list"></i>{{__('List View')}}</span>
            </a>
        @else
            <a href="{{ route('bugs.view', 'grid') }}" class="btn btn-xs btn-white btn-icon-only width-auto">
                <span class="btn-inner--text"><i class="fas fa-table"></i>{{__('Card View')}}</span>
            </a>
        @endif
      </div>
      <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
        @can('manage project')

            <a href="{{ route('projects.index') }}" class="btn btn-xs btn-white btn-icon-only width-auto">
                <span class="btn-inner--icon"><i class="fas fa-arrow-left"></i>{{__('Back')}}</span>
            </a>
        @endcan
      </div>
    </div>
@endsection

@section('content')
<div class="row">
<div class="col-12">
    <div class="card">
        <div class="table-responsive">
            <table class="table align-items-center">
                <thead>
                <tr>
                    <th scope="col">{{__('Name')}}</th>
                    <th scope="col">{{__('Bug Status')}}</th>
                    <th scope="col">{{__('Priority')}}</th>
                    <th scope="col">{{__('End Date')}}</th>
                    <th scope="col">{{__('created By')}}</th>
                    <th scope="col">{{__('Assigned To')}}</th>
                    <th scope="col"></th>
                </tr>
                </thead>
                <tbody class="list">
                @if(count($bugs) > 0)
                    @foreach($bugs as $bug)
                        <tr>
                            <td>
                                <span class="h6 text-sm font-weight-bold mb-0"><a href="{{ route('task.bug',$bug->project_id) }}">{{ $bug->title }}</a></span>
                                <span class="d-block text-sm text-muted">{{ $bug->project->project_name }}
                                    <span class="badge badge-xs badge-{{ (\Auth::user()->checkProject($bug->project_id) == 'Owner') ? 'success' : 'warning'  }}">{{ __(\Auth::user()->checkProject($bug->project_id)) }}</span>
                                </span>
                            </td>
                            <td>{{ $bug->bug_status->title }}</td>
                            <td>
                                <span class="badge badge-pill badge-sm badge-{{__(\App\ProjectTask::$priority_color[$bug->priority])}}">{{ __(\App\ProjectTask::$priority[$bug->priority]) }}</span>
                            </td>
                            <td class="{{ (strtotime($bug->due_date) < time()) ? 'text-danger' : '' }}">{{ \App\Utility::getDateFormated($bug->due_date) }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    {{ $bug->createdBy->name }}
                                </div>
                            </td>
                            <td>
                                <div class="avatar-group">
                                    @if($bug->users()->count() > 0)
                                    @php $user = $bug->users(); @endphp

                                    <a href="#" class="avatar rounded-circle avatar-sm">
                                        <img data-original-title="{{(!empty($user[0])?$user[0]->name:'')}}" @if($user[0]->avatar) src="{{asset('/storage/uploads/avatar/'.$user[0]->avatar)}}" @else src="{{asset('assets/img/avatar/avatar-1.png')}}" @endif title="{{ $user[0]->name }}" class="hweb">
                                    </a>
                                        @if($users = $bug->users())
                                            @foreach($users as $key => $user)
                                                @if($key<3)

                                                @else
                                                    @break
                                                @endif
                                            @endforeach
                                        @endif
                                        @if(count($users) > 3)
                                            <a href="#" class="avatar rounded-circle avatar-sm">
                                                <img  src="{{$user->getImgImageAttribute()}}">
                                            </a>
                                        @endif
                                    @else
                                        {{ __('-') }}
                                    @endif
                                </div>
                            </td>

                            <td class="text-right w-15">
                                <div class="actions">
                                    <a class="action-item px-1" data-toggle="tooltip" data-original-title="{{__('Attachment')}}">
                                        <i class="fas fa-paperclip mr-2"></i>{{ count($bug->bugFiles) }}
                                    </a>
                                    <a class="action-item px-1" data-toggle="tooltip" data-original-title="{{__('Comment')}}">
                                        <i class="fas fa-comment-alt mr-2"></i>{{ count($bug->comments) }}
                                    </a>
                                    <a class="action-item px-1" data-toggle="tooltip" data-original-title="{{__('Checklist')}}">
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <th scope="col" colspan="7"><h6 class="text-center">{{__('No tasks found')}}</h6></th>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
</div>

@endsection
