@extends('layouts.admin')
@section('page-title')
    {{__('Manage Trainer')}}
@endsection

@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
        @can('create trainer')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
            <a href="#" data-url="{{ route('trainer.create') }}" class="btn btn-xs btn-white btn-icon-only width-auto" data-ajax-popup="true" data-title="{{__('Create New Trainer')}}">
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
                        <table class="table table-striped mb-0 dataTable" >
                            <thead>
                            <tr>
                                <th>{{__('Branch')}}</th>
                                <th>{{__('Full Name')}}</th>
                                <th>{{__('Contact')}}</th>
                                <th>{{__('Email')}}</th>
                                @if( Gate::check('edit trainer') ||Gate::check('delete trainer') ||Gate::check('show trainer'))
                                    <th width="200px">{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody class="font-style">
                            @foreach ($trainers as $trainer)
                                <tr>
                                    <td>{{ !empty($trainer->branches)?$trainer->branches->name:'' }}</td>
                                    <td>{{$trainer->firstname .' '.$trainer->lastname}}</td>
                                    <td>{{$trainer->contact}}</td>
                                    <td>{{$trainer->email}}</td>
                                    @if( Gate::check('edit trainer') ||Gate::check('delete trainer') || Gate::check('show trainer'))
                                        <td>
                                            @can('show trainer')
                                                <a href="#" data-url="{{ route('trainer.show',$trainer->id) }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Trainer Detail')}}" class="edit-icon bg-success" data-toggle="tooltip" data-original-title="{{__('View Detail')}}"><i class="fas fa-eye"></i></a>
                                            @endcan
                                            @can('edit trainer')
                                                <a href="#" data-url="{{ route('trainer.edit',$trainer->id) }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Trainer')}}" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="fas fa-pencil-alt"></i></a>
                                            @endcan
                                            @can('delete trainer')
                                                <a href="#" class="delete-icon" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$trainer->id}}').submit();" data-toggle="tooltip" data-original-title="{{__('Delete')}}"><i class="fas fa-trash"></i></a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['trainer.destroy', $trainer->id],'id'=>'delete-form-'.$trainer->id]) !!}
                                                {!! Form::close() !!}
                                            @endcan
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
