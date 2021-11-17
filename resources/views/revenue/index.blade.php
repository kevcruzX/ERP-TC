@extends('layouts.admin')
@section('page-title')
    {{__('Manage Revenues')}}
@endsection

@section('action-button')

    @can('create revenue')
    <div class="row d-flex justify-content-end">
      <div class="col-auto">
        {{ Form::open(array('route' => array('revenue.index'),'method' => 'GET','id'=>'revenue_form')) }}
      </div>
      <div class="col-2">
          <div class="all-select-box">
              <div class="btn-box">
                  {{ Form::label('date', __('Date'),['class'=>'text-type']) }}
                  {{ Form::text('date', isset($_GET['date'])?$_GET['date']:null, array('class' => 'month-btn form-control datepicker-range')) }}
              </div>
          </div>
      </div>
      <div class="col-auto">
          <div class="all-select-box">
              <div class="btn-box">
                  {{ Form::label('account', __('Account'),['class'=>'text-type']) }}
                  {{ Form::select('account',$account,isset($_GET['account'])?$_GET['account']:'', array('class' => 'form-control select2')) }}
              </div>
          </div>
      </div>
      <div class="col-auto">
          <div class="all-select-box">
              <div class="btn-box">
                  {{ Form::label('customer', __('Customer'),['class'=>'text-type']) }}
                  {{ Form::select('customer',$customer,isset($_GET['customer'])?$_GET['customer']:'', array('class' => 'form-control select2')) }}
              </div>
          </div>
      </div>
      <div class="col-auto">
          <div class="all-select-box">
              <div class="btn-box">
                  {{ Form::label('category', __('Category'),['class'=>'text-type']) }}
                  {{ Form::select('category',$category,isset($_GET['category'])?$_GET['category']:'', array('class' => 'form-control select2')) }}
              </div>
          </div>
      </div>
      <div class="col-auto my-custom">
          <a href="#" class="apply-btn" onclick="document.getElementById('revenue_form').submit(); return false;" data-toggle="tooltip" data-original-title="{{__('apply')}}">
              <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
          </a>
          <a href="{{route('revenue.index')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
              <span class="btn-inner--icon"><i class="fas fa-trash-restore-alt"></i></span>
          </a>
      </div>
      {{ Form::close() }}
      <div class="col-2 my-custom-btn">
          <div class="all-button-box">
              <a href="#" data-url="{{ route('revenue.create') }}" data-ajax-popup="true" data-title="{{__('Create New Revenue')}}" class="btn btn-xs btn-white btn-icon-only width-auto">
                  <i class="fas fa-plus"></i> {{__('Create')}}
              </a>
          </div>
      </div>
    </div>
    @endcan
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
                                <th> {{__('Amount')}}</th>
                                <th> {{__('Account')}}</th>
                                <th> {{__('Customer')}}</th>
                                <th> {{__('Category')}}</th>
                                <th> {{__('Reference')}}</th>
                                <th> {{__('Description')}}</th>
                                @if(Gate::check('edit revenue') || Gate::check('delete revenue'))
                                    <th> {{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($revenues as $revenue)
                                <tr class="font-style">
                                    <td>{{  Auth::user()->dateFormat($revenue->date)}}</td>
                                    <td>{{  Auth::user()->priceFormat($revenue->amount)}}</td>
                                    <td>{{ !empty($revenue->bankAccount)?$revenue->bankAccount->bank_name.' '.$revenue->bankAccount->holder_name:''}}</td>
                                    <td>{{  (!empty($revenue->customer)?$revenue->customer->name:'-')}}</td>
                                    <td>{{  !empty($revenue->category)?$revenue->category->name:'-'}}</td>
                                    <td>{{  !empty($revenue->reference)?$revenue->reference:'-'}}</td>
                                    <td>{{  !empty($revenue->description)?$revenue->description:'-'}}</td>
                                    @if(Gate::check('edit revenue') || Gate::check('delete revenue'))
                                        <td class="Action">
                                            <span>
                                            @can('edit revenue')
                                                    <a href="#" class="edit-icon" data-url="{{ route('revenue.edit',$revenue->id) }}" data-ajax-popup="true" data-title="{{__('Edit Revenue')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                @endcan
                                                @can('delete revenue')
                                                    <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$revenue->id}}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['revenue.destroy', $revenue->id],'id'=>'delete-form-'.$revenue->id]) !!}
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
