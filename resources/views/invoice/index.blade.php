@extends('layouts.admin')
@section('page-title')
    {{__('Manage Invoices')}}
@endsection
@push('script-page')
    <script>

        $('.copy_link').click(function (e) {
            e.preventDefault();
            var copyText = $(this).attr('href');

            document.addEventListener('copy', function (e) {
                e.clipboardData.setData('text/plain', copyText);
                e.preventDefault();
            }, true);

            document.execCommand('copy');
            show_toastr('Success', 'Url copied to clipboard', 'success');
        });
    </script>
@endpush
@section('action-button')
    @can('create invoice')
        <div class="row d-flex justify-content-end">
          <div class="col-2">
            @if(!\Auth::guard('customer')->check())
                {{ Form::open(array('route' => array('invoice.index'),'method' => 'GET','id'=>'customer_submit')) }}
            @else
                {{ Form::open(array('route' => array('customer.invoice'),'method' => 'GET','id'=>'customer_submit')) }}
            @endif
          </div>
          <div class="col-2">
            <div class="all-select-box">
                <div class="btn-box">
                    {{ Form::label('issue_date', __('Date'),['class'=>'text-type']) }}
                    {{ Form::text('issue_date', isset($_GET['issue_date'])?$_GET['issue_date']:null, array('class' => 'form-control month-btn datepicker-range')) }}
                </div>
            </div>
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
                      {{ Form::label('status', __('Status'),['class'=>'text-type']) }}
                      {{ Form::select('status', [''=>'All']+$status,isset($_GET['status'])?$_GET['status']:'', array('class' => 'form-control select2')) }}
                  </div>
              </div>
          </div>
          <div class="col-auto my-custom">
              <a href="#" class="apply-btn" onclick="document.getElementById('customer_submit').submit(); return false;" data-toggle="tooltip" data-original-title="{{__('apply')}}">
                  <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
              </a>
              @if(!\Auth::guard('customer')->check())
                  <a href="{{route('invoice.index')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
                      <span class="btn-inner--icon"><i class="fas fa-trash-restore-alt"></i></span>
                  </a>
              @else
                  <a href="{{route('customer.index')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
                      <span class="btn-inner--icon"><i class="fas fa-trash-restore-alt"></i></span>
                  </a>
              @endif
          </div>
          {{ Form::close() }}
          <div class="col-2 my-custom-btn">
              <div class="all-button-box">
                  <a href="{{ route('invoice.create',0) }}" class="btn btn-xs btn-white btn-icon-only width-auto">
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
                                <th> {{__('Invoice')}}</th>
                                @if(!\Auth::guard('customer')->check())
                                    <th>{{__('Customer')}}</th>
                                @endif
                                <th>{{__('Issue Date')}}</th>
                                <th>{{__('Due Date')}}</th>
                                <th>{{__('Due Amount')}}</th>
                                <th>{{__('Status')}}</th>
                                @if(Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice'))
                                    <th>{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($invoices as $invoice)
                                <tr>
                                    <td class="Id">
                                        @if(\Auth::guard('customer')->check())
                                            <a href="{{ route('customer.invoice.show',\Crypt::encrypt($invoice->id)) }}">{{ AUth::user()->invoiceNumberFormat($invoice->invoice_id) }}</a>
                                        @else
                                            <a href="{{ route('invoice.show',\Crypt::encrypt($invoice->id)) }}">{{ AUth::user()->invoiceNumberFormat($invoice->invoice_id) }}</a>
                                        @endif
                                    </td>
                                    @if(!\Auth::guard('customer')->check())
                                        <td> {{!empty($invoice->customer)? $invoice->customer->name:'' }} </td>
                                    @endif
                                    <td>{{ Auth::user()->dateFormat($invoice->issue_date) }}</td>
                                    <td>
                                        @if(($invoice->due_date < date('Y-m-d')))
                                            <p class="text-danger"> {{ \Auth::user()->dateFormat($invoice->due_date) }}</p>
                                        @else
                                            {{ \Auth::user()->dateFormat($invoice->due_date) }}
                                        @endif
                                    </td>
                                    <td>{{\Auth::user()->priceFormat($invoice->getDue())  }}</td>
                                    <td>
                                        @if($invoice->status == 0)
                                            <span class="badge badge-pill badge-primary">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 1)
                                            <span class="badge badge-pill badge-warning">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 2)
                                            <span class="badge badge-pill badge-danger">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 3)
                                            <span class="badge badge-pill badge-info">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 4)
                                            <span class="badge badge-pill badge-success">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @endif
                                    </td>
                                    @if(Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice'))
                                        <td class="Action">
                                            <span>@php $invoiceID= Crypt::encrypt($invoice->id); @endphp
                                              @can('copy invoice')
                                              <a href="{{ route('invoice.link.copy',[$invoiceID]) }}" class="edit-icon bg-info copy_link" data-toggle="tooltip" data-original-title="{{__('Click to copy')}}"><i class="fas fa-link"></i></a>
                                              @endcan
                                              @can('duplicate invoice')
                                              <a href="#" class="edit-icon bg-success" data-toggle="tooltip" data-original-title="{{__('Duplicate')}}" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="You want to confirm this action. Press Yes to continue or Cancel to go back" data-confirm-yes="document.getElementById('duplicate-form-{{$invoice->id}}').submit();">
                                                    <i class="fas fa-copy"></i>
                                                    {!! Form::open(['method' => 'get', 'route' => ['invoice.duplicate', $invoice->id],'id'=>'duplicate-form-'.$invoice->id]) !!}
                                                        {!! Form::close() !!}
                                                </a>
                                                @endcan
                                                @can('show invoice')
                                                    @if(\Auth::guard('customer')->check())

                                                    @else
                                                        <a href="{{ route('invoice.show',\Crypt::encrypt($invoice->id)) }}" class="edit-icon bg-info" data-toggle="tooltip" data-original-title="{{__('Detail')}}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @endif
                                                @endcan
                                                @can('edit invoice')
                                                    <a href="{{ route('invoice.edit',Crypt::encrypt($invoice->id)) }}" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                @endcan
                                                @can('delete invoice')
                                                    <a href="#" class="delete-icon " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$invoice->id}}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['invoice.destroy', $invoice->id],'id'=>'delete-form-'.$invoice->id]) !!}
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
