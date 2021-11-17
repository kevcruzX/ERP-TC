@extends('layouts.admin')
@section('page-title')
    {{__('Manage Custom Field')}}
@endsection

@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
        @can('create constant custom field')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-url="{{ route('custom-field.create') }}" data-ajax-popup="true" data-title="{{__('Create New Custom Field')}}" class="btn btn-xs btn-white btn-icon-only width-auto">
                    <i class="fas fa-plus"></i> {{__('Create')}}
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
                                <th> {{__('Custom Field')}}</th>
                                <th> {{__('Type')}}</th>
                                <th> {{__('Module')}}</th>
                                <th> {{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($custom_fields as $field)
                                <tr>
                                    <td>{{ $field->name}}</td>
                                    <td>{{ $field->type}}</td>
                                    <td>{{ $field->module}}</td>
                                    @if(Gate::check('edit constant custom field') || Gate::check('delete constant custom field'))
                                        <td class="Action">
                                            <span>
                                            @can('edit constant custom field')
                                                    <a href="#" class="edit-icon" data-url="{{ route('custom-field.edit',$field->id) }}" data-ajax-popup="true" data-title="{{__('Edit Custom Field')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                @endcan
                                                @can('delete constant custom field')
                                                    <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$field->id}}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['custom-field.destroy', $field->id],'id'=>'delete-form-'.$field->id]) !!}
                                                    {!! Form::close() !!}
                                                @endcan
                                            </span>
                                        </td>

                                    @endif
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
