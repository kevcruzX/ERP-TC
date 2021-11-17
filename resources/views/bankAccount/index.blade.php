@extends('layouts.admin')
@section('page-title')
    {{__('Manage Bank Account')}}
@endsection

@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
        @can('create bank account')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-url="{{ route('bank-account.create') }}" data-ajax-popup="true" data-title="{{__('Create New Account')}}" class="btn btn-xs btn-white btn-icon-only width-auto">
                    <i class="fas fa-plus"></i> {{__('Create')}}
                </a>
            </div>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr>
                                <th> {{__('Name')}}</th>
                                <th> {{__('Bank')}}</th>
                                <th> {{__('Account Number')}}</th>
                                <th> {{__('Current Balance')}}</th>
                                <th> {{__('Contact Number')}}</th>
                                <th> {{__('Bank Branch')}}</th>
                                @if(Gate::check('edit bank account') || Gate::check('delete bank account'))
                                    <th> {{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($accounts as $account)
                                <tr class="font-style">
                                    <td>{{  $account->holder_name}}</td>
                                    <td>{{  $account->bank_name}}</td>
                                    <td>{{  $account->account_number}}</td>
                                    <td>{{  \Auth::user()->priceFormat($account->opening_balance)}}</td>
                                    <td>{{  $account->contact_number}}</td>
                                    <td>{{  $account->bank_address}}</td>
                                    @if(Gate::check('edit bank account') || Gate::check('delete bank account'))
                                        <td class="Action">
                                            <span>
                                            @if($account->holder_name!='Cash')
                                                    @can('edit bank account')
                                                        <a href="#" class="edit-icon" data-url="{{ route('bank-account.edit',$account->id) }}" data-ajax-popup="true" data-title="{{__('Edit Account')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    @endcan
                                                    @can('delete bank account')
                                                        <a href="#" class="delete-icon " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$account->id}}').submit();">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['bank-account.destroy', $account->id],'id'=>'delete-form-'.$account->id]) !!}
                                                        {!! Form::close() !!}
                                                    @endcan
                                                @else
                                                    -
                                                @endif
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
