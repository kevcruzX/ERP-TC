@extends('layouts.admin')
@section('page-title')
    {{__('Manage Bug Report')}}
@endsection

@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
        @can('create bug report')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-url="{{ route('task.bug.create',$project->id) }}" data-ajax-popup="true" data-title="{{__('Create Bug')}}" class="btn btn-xs btn-white btn-icon-only width-auto"><i class="fas fa-plus"></i> {{__('Create')}}</a>
            </div>
        @endcan
        @can('manage bug report')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="{{ route('task.bug.kanban',$project->id) }}" class="btn btn-xs btn-white btn-icon-only width-auto"><i class="fas fa-table"></i> {{__('Bug Kanban')}} </a>
            </div>
        @endcan
        @can('manage project')
          <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
            <a href="{{ route('projects.show',$project->id) }}" class="btn btn-xs btn-white btn-icon-only width-auto">
                <span class="btn-inner--icon"><i class="fas fa-arrow-left"></i>{{__('Back')}}</span>
            </a>
          </div>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-striped dataTable">
                        <thead>
                        <tr>
                            <th> {{__('Bug Id')}}</th>
                            <th> {{__('Assign To')}}</th>
                            <th> {{__('Bug Title')}}</th>
                            <th> {{__('Start Date')}}</th>
                            <th> {{__('Due Date')}}</th>
                            <th> {{__('Status')}}</th>
                            <th> {{__('Priority')}}</th>
                            <th> {{__('Created By')}}</th>
                            <th width="10%"> {{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($bugs as $bug)
                            <tr>
                                <td>{{ \Auth::user()->bugNumberFormat($bug->bug_id)}}</td>
                                <td>{{ (!empty($bug->assignTo)?$bug->assignTo->name:'') }}</td>
                                <td>{{ $bug->title}}</td>
                                <td>{{ Auth::user()->dateFormat($bug->start_date) }}</td>
                                <td>{{ Auth::user()->dateFormat($bug->due_date) }}</td>
                                <td>{{ (!empty($bug->bug_status)?$bug->bug_status->title:'') }}</td>
                                <td>{{ $bug->priority }}</td>
                                <td>{{ $bug->createdBy->name }}</td>
                                <td class="Action" width="10%">
                                    @can('edit bug report')
                                        <a href="#" class="edit-icon" data-url="{{ route('task.bug.edit',[$project->id,$bug->id]) }}" data-ajax-popup="true" data-title="{{__('Edit Bug Report')}}">
                                            <i class="fas fa-pencil-alt"></i>
                                        </a>
                                    @endcan
                                    @can('delete bug report')
                                        <a href="#" class="delete-icon" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$bug->id}}').submit();">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['task.bug.destroy', $project->id,$bug->id],'id'=>'delete-form-'.$bug->id]) !!}
                                        {!! Form::close() !!}
                                    @endcan
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
