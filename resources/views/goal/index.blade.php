@extends('layouts.admin')
@section('page-title')
    {{__('Manage Goals')}}
@endsection

@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
        @can('create goal')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-url="{{ route('goal.create') }}" data-ajax-popup="true" data-title="{{__('Create New Goal')}}" class="btn btn-xs btn-white btn-icon-only width-auto">
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
                                <th> {{__('Name')}}</th>
                                <th> {{__('Type')}}</th>
                                <th> {{__('From')}}</th>
                                <th> {{__('To')}}</th>
                                <th> {{__('Amount')}}</th>
                                <th> {{__('Is Dashboard Display')}}</th>
                                <th> {{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($golas as $gola)
                                <tr>
                                    <td class="font-style">{{ $gola->name }}</td>
                                    <td class="font-style"> {{ __(\App\Goal::$goalType[$gola->type]) }} </td>
                                    <td class="font-style">{{ $gola->from }}</td>
                                    <td class="font-style">{{ $gola->to }}</td>
                                    <td class="font-style">{{ \Auth::user()->priceFormat($gola->amount) }}</td>
                                    <td class="font-style">{{$gola->is_display==1 ? __('Yes') :__('No')}}</td>
                                    <td class="Action">
                                        <span>
                                        @can('edit goal')
                                                <a href="#" class="edit-icon" data-url="{{ route('goal.edit',$gola->id) }}" data-ajax-popup="true" data-title="{{__('Edit Goal')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            @endcan
                                            @can('delete goal')
                                                <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$gola->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['goal.destroy', $gola->id],'id'=>'delete-form-'.$gola->id]) !!}
                                                {!! Form::close() !!}
                                            @endcan
                                        </span>
                                    </td>
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
