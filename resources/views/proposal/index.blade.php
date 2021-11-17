@extends('layouts.admin')
@section('page-title')
    {{__('Manage Proposals')}}
@endsection

@section('action-button')
  <div class="row d-flex justify-content-end mt-2">
    @can('create proposal')
        <div class="col-auto">
          @if(!\Auth::guard('customer')->check())
              {{ Form::open(array('route' => array('proposal.index'),'method' => 'GET','id'=>'frm_submit')) }}
          @else
              {{ Form::open(array('route' => array('customer.proposal'),'method' => 'GET','id'=>'frm_submit')) }}
          @endif
        </div>
        @if(!\Auth::guard('customer')->check())
            <div class="col-auto">
                <div class="all-select-box">
                    <div class="btn-box">
                        {{ Form::label('customer', __('Customer'),['class'=>'text-type']) }}
                        {{ Form::select('customer',$customer,isset($_GET['customer'])?$_GET['customer']:'', array('class' => 'form-control select2')) }}
                    </div>
                </div>
            </div>
        @endif
        <div class="col-auto">
            <div class="all-select-box">
                <div class="btn-box">
                    {{ Form::label('issue_date', __('Date'),['class'=>'text-type']) }}
                    {{ Form::text('issue_date', isset($_GET['issue_date'])?$_GET['issue_date']:null, array('class' => 'form-control datepicker-range month-btn')) }}
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="all-select-box">
                <div class="btn-box">
                    {{ Form::label('status', __('Status'),['class'=>'text-type']) }}
                    {{ Form::select('status', [''=>'All']+$status,isset($_GET['status'])?$_GET['status']:'', array('class' => 'form-control select2')) }}
                </div>
            </div>
        </div>
        <div class="col-auto my-custom">
            <a href="#" class="apply-btn" onclick="document.getElementById('frm_submit').submit(); return false;" data-toggle="tooltip" data-original-title="{{__('apply')}}">
                <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
            </a>
            @if(!\Auth::guard('customer')->check())
                <a href="{{route('proposal.index')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
                    <span class="btn-inner--icon"><i class="fas fa-trash-restore-alt"></i></span>
                </a>
            @else
                <a href="{{route('customer.proposal')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
                    <span class="btn-inner--icon"><i class="fas fa-trash-restore-alt"></i></span>
                </a>
            @endif
        </div>
        <div class="col-auto">
            {{ Form::close() }}
        </div>
        <div class="col-2 my-custom">
            <div class="all-button-box">
                <a href="{{ route('proposal.create',0) }}" class="btn btn-xs btn-white btn-icon-only width-auto">
                    <i class="fa fa-plus"></i> {{__('Create')}}
                </a>
            </div>
        </div>

    @endcan
  </div>
@endsection

@section('content')
    <div class="">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr>
                                <th> {{__('Proposal')}}</th>
                                @if(!\Auth::guard('customer')->check())
                                    <th> {{__('Customer')}}</th>
                                @endif
                                <th> {{__('Category')}}</th>
                                <th> {{__('Issue Date')}}</th>
                                <th> {{__('Status')}}</th>
                                @if(Gate::check('edit proposal') || Gate::check('delete proposal') || Gate::check('show proposal'))
                                    <th> {{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($proposals as $proposal)
                                <tr class="font-style">
                                    <td class="Id">
                                        @if(\Auth::guard('customer')->check())
                                            <a href="{{ route('customer.proposal.show',\Crypt::encrypt($proposal->id)) }}">{{ AUth::user()->proposalNumberFormat($proposal->proposal_id) }}
                                            </a>
                                        @else
                                            <a href="{{ route('proposal.show',\Crypt::encrypt($proposal->id)) }}">{{ AUth::user()->proposalNumberFormat($proposal->proposal_id) }}
                                            </a>
                                        @endif
                                    </td>
                                    @if(!\Auth::guard('customer')->check())
                                        <td> {{!empty($proposal->customer)? $proposal->customer->name:'' }} </td>
                                    @endif
                                    <td>{{ !empty($proposal->category)?$proposal->category->name:''}}</td>
                                    <td>{{ Auth::user()->dateFormat($proposal->issue_date) }}</td>
                                    <td>
                                        @if($proposal->status == 0)
                                            <span class="badge badge-pill badge-primary">{{ __(\App\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 1)
                                            <span class="badge badge-pill badge-info">{{ __(\App\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 2)
                                            <span class="badge badge-pill badge-success">{{ __(\App\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 3)
                                            <span class="badge badge-pill badge-warning">{{ __(\App\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 4)
                                            <span class="badge badge-pill badge-danger">{{ __(\App\Proposal::$statues[$proposal->status]) }}</span>
                                        @endif
                                    </td>
                                    @if(Gate::check('edit proposal') || Gate::check('delete proposal') || Gate::check('show proposal'))
                                        <td class="Action">
                                            @if($proposal->is_convert==0)
                                                @can('convert invoice')
                                                    <a href="#" class="edit-icon bg-warning" data-toggle="tooltip" data-original-title="{{__('Convert to Invoice')}}" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('You want to confirm convert to invoice. Press Yes to continue or Cancel to go back')}}" data-confirm-yes="document.getElementById('proposal-form-{{$proposal->id}}').submit();">
                                                        <i class="fas fa-exchange-alt"></i>
                                                        {!! Form::open(['method' => 'get', 'route' => ['proposal.convert', $proposal->id],'id'=>'proposal-form-'.$proposal->id]) !!}
                                                        {!! Form::close() !!}
                                                    </a>
                                                @endcan
                                            @else
                                                @can('convert invoice')
                                                    <a href="{{ route('invoice.show',\Crypt::encrypt($proposal->converted_invoice_id)) }}" class="edit-icon bg-warning" data-toggle="tooltip" data-original-title="{{__('Already convert to Invoice')}}" data-toggle="tooltip" data-original-title="{{__('Delete')}}">
                                                        <i class="fas fa-file"></i>
                                                    </a>
                                                @endcan
                                            @endif
                                            @can('duplicate proposal')
                                                <a href="#" class="edit-icon bg-success" data-toggle="tooltip" data-original-title="{{__('Duplicate')}}" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('You want to confirm duplicate this invoice. Press Yes to continue or Cancel to go back')}}" data-confirm-yes="document.getElementById('duplicate-form-{{$proposal->id}}').submit();">
                                                    <i class="fas fa-copy"></i>
                                                    {!! Form::open(['method' => 'get', 'route' => ['proposal.duplicate', $proposal->id],'id'=>'duplicate-form-'.$proposal->id]) !!}
                                                    {!! Form::close() !!}
                                                </a>
                                            @endcan
                                            @can('show proposal')
                                                @if(\Auth::guard('customer')->check())
                                                    <a href="{{ route('customer.proposal.show',\Crypt::encrypt($proposal->id)) }}" class="edit-icon bg-info" data-toggle="tooltip" data-original-title="{{__('Detail')}}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @else
                                                    <a href="{{ route('proposal.show',\Crypt::encrypt($proposal->id)) }}" class="edit-icon bg-info" data-toggle="tooltip" data-original-title="{{__('Detail')}}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                @endif
                                            @endcan
                                            @can('edit proposal')
                                                <a href="{{ route('proposal.edit',\Crypt::encrypt($proposal->id)) }}" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            @endcan

                                            @can('delete proposal')
                                                <a href="#" class="delete-icon " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$proposal->id}}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['proposal.destroy', $proposal->id],'id'=>'delete-form-'.$proposal->id]) !!}
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
