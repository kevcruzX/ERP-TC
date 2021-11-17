@extends('layouts.admin')
@section('page-title')
    {{__('Bank Balance Transfer')}}
@endsection

@section('action-button')
  <div class="row d-flex justify-content-end mt-2">
    @can('create transfer')
        <div class="col-auto">
          {{ Form::open(array('route' => array('bank-transfer.index'),'method' => 'GET','id'=>'transfer_form')) }}
        </div>
        <div class="col-auto">
          <div class="btn-box">
              {{ Form::label('date', __('Date'),['class'=>'text-type']) }}
              {{ Form::text('date', isset($_GET['date'])?$_GET['date']:'', array('class' => 'form-control month-btn datepicker-range')) }}
          </div>
        </div>
        <div class="col-auto">
          <div class="btn-box">
              {{ Form::label('f_account', __('From Account'),['class'=>'text-type']) }}
              {{ Form::select('f_account',$account,isset($_GET['f_account'])?$_GET['f_account']:'', array('class' => 'form-control select2')) }}
          </div>
        </div>
        <div class="col-auto">
          <div class="btn-box">
              {{ Form::label('t_account', __('To Account'),['class'=>'text-type']) }}
              {{ Form::select('t_account', $account,isset($_GET['t_account'])?$_GET['t_account']:'', array('class' => 'form-control select2')) }}
          </div>
        </div>
        <div class="col-auto my-custom">
              <a href="#" class="apply-btn" onclick="document.getElementById('bank-transfer_form').submit(); return false;" data-toggle="tooltip" data-original-title="{{__('apply')}}">
                  <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
              </a>
              <a href="{{route('bank-transfer.index')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
                  <span class="btn-inner--icon"><i class="fas fa-trash-restore-alt"></i></span>
              </a>
        </div>
        <div class="col-2 my-custom-btn">
            <div class="all-button-box">
                <a href="#" data-url="{{ route('bank-transfer.create') }}" data-ajax-popup="true" data-title="{{__('Create New Transfer Amount')}}" class="btn btn-xs btn-white btn-icon-only width-auto">
                    <i class="fa fa-plus"></i> {{__('Create')}}
                </a>
            </div>
        </div>
          {{ Form::close() }}
    @endcan
  </div>
@endsection

@section('content')
    <div class="">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body py-0 mt-2">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr>
                                <th> {{__('Date')}}</th>
                                <th> {{__('From Account')}}</th>
                                <th> {{__('To Account')}}</th>
                                <th> {{__('Amount')}}</th>
                                <th> {{__('Reference')}}</th>
                                <th> {{__('Description')}}</th>
                                @if(Gate::check('edit transfer') || Gate::check('delete transfer'))
                                    <th> {{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($transfers as $transfer)
                                <tr class="font-style">
                                    <td>{{ \Auth::user()->dateFormat( $transfer->date) }}</td>
                                    <td>{{ !empty($transfer->fromBankAccount())? $transfer->fromBankAccount()->bank_name.' '.$transfer->fromBankAccount()->holder_name:''}}</td>
                                    <td>{{!empty( $transfer->toBankAccount())? $transfer->toBankAccount()->bank_name.' '. $transfer->toBankAccount()->holder_name:''}}</td>
                                    <td>{{  \Auth::user()->priceFormat( $transfer->amount)}}</td>
                                    <td>{{  $transfer->reference}}</td>
                                    <td>{{  $transfer->description}}</td>
                                    @if(Gate::check('edit transfer') || Gate::check('delete transfer'))
                                        <td class="Action">
                                            <span>
                                            @can('edit transfer')
                                                    <a href="#" class="edit-icon" data-url="{{ route('bank-transfer.edit',$transfer->id) }}" data-ajax-popup="true" data-title="{{__('Edit Transfer Amount')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                @endcan
                                                @can('delete transfer')
                                                    <a href="#" class="delete-icon " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$transfer->id}}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['transfer.destroy', $transfer->id],'id'=>'delete-form-'.$transfer->id]) !!}
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
