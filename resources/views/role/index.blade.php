@extends('layouts.admin')
@section('page-title')
    {{__('Manage Roles')}}
@endsection

@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
        @can('create role')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-url="{{ route('roles.create') }}" data-size="xl" data-ajax-popup="true" data-title="{{__('Create New Role')}}" class="btn btn-xs btn-white btn-icon-only width-auto">
                    <i class="fa fa-plus"></i> {{__('Create')}}
                </a>
            </div>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr>
                                <th>{{__('Role')}} </th>
                                <th>{{__('Permissions')}} </th>
                                <th width="150">{{__('Action')}} </th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($roles as $role)
                            @if($role->name != 'client')
                                <tr class="font-style">
                                    <td class="Role">{{ $role->name }}</td>
                                    <td class="Permission">
                                        @for($j=0;$j<count($role->permissions()->pluck('name'));$j++)
                                            <a href="#" class="absent-btn">{{$role->permissions()->pluck('name')[$j]}}</a>
                                        @endfor
                                    </td>
                                    <td class="Action">
                                        <span>
                                        @can('edit role')
                                                <a href="#" class="edit-icon" data-url="{{ route('roles.edit',$role->id) }}" data-size="xl" data-ajax-popup="true" data-toggle="tooltip" data-original-title="{{__('Edit')}}" data-title="{{__('Edit Role')}}">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            @endcan
                                            @can('delete role')
                                                <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$role->id}}').submit();"><i class="fas fa-trash"></i></a>

                                                {!! Form::open(['method' => 'DELETE', 'route' => ['roles.destroy', $role->id],'id'=>'delete-form-'.$role->id]) !!}
                                                {!! Form::close() !!}
                                            @endcan
                                        </span>
                                    </td>
                                </tr>
                              @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
