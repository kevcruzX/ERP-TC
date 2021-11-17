@extends('layouts.admin')
@push('script-page')
@endpush
@section('page-title')
    {{__('Manage Customer-Detail')}}
@endsection

@section('action-button')
    <div class="row d-flex justify-content-end">
        @can('create invoice')
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="all-button-box">
                    <a href="{{ route('invoice.create',$customer->id) }}" class="btn btn-xs btn-white btn-icon-only width-auto">
                        {{__('Create Invoice')}}
                    </a>
                </div>
            </div>
        @endcan
        @can('create proposal')
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6">
                <div class="all-button-box">
                    <a href="{{ route('proposal.create',$customer->id) }}" class="btn btn-xs btn-white btn-icon-only width-auto">
                        {{__('Create Proposal')}}
                    </a>
                </div>
            </div>
        @endcan
        @can('edit customer')
            <div class="col-xl-1 col-lg-2 col-md-2 col-sm-6 col-6">
                <div class="all-button-box">
                    <a href="#" data-size="2xl" data-url="{{ route('customer.edit',$customer['id']) }}" data-ajax-popup="true" data-title="{{__('Edit Customer')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}" class="btn btn-xs btn-white btn-icon-only width-auto">
                        <i class="fa fa-pencil-alt"></i>
                    </a>
                </div>
            </div>
        @endcan
        @can('delete customer')
            <div class="col-xl-1 col-lg-2 col-md-2 col-sm-6 col-6">
                <div class="all-button-box">
                    <a href="#" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{ $customer['id']}}').submit();" class="btn btn-xs btn-white bg-danger btn-icon-only width-auto">
                        <i class="fa fa-trash"></i>
                    </a>
                    {!! Form::open(['method' => 'DELETE', 'route' => ['customer.destroy', $customer['id']],'id'=>'delete-form-'.$customer['id']]) !!}
                    {!! Form::close() !!}
                </div>
            </div>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-md-4 col-lg-4 col-xl-4">
            <div class="card pb-0 customer-detail-box">
                <h3 class="small-title">{{__('Customer Info')}}</h3>
                <div class="p-4">
                    <h5 class="report-text gray-text mb-0">{{$customer['name']}}</h5>
                    <h5 class="report-text gray-text mb-0">{{$customer['email']}}</h5>
                    <h5 class="report-text gray-text mb-0">{{$customer['contact']}}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-4 col-xl-4">
            <div class="card pb-0 customer-detail-box">
                <h3 class="small-title">{{__('Billing Info')}}</h3>
                <div class="p-4">
                    <h5 class="report-text gray-text mb-0">{{$customer['billing_name']}}</h5>
                    <h5 class="report-text gray-text mb-0">{{$customer['billing_phone']}}</h5>
                    <h5 class="report-text gray-text mb-0">{{$customer['billing_address']}}</h5>
                    <h5 class="report-text gray-text mb-0">{{$customer['billing_zip']}}</h5>
                    <h5 class="report-text gray-text mb-0">{{$customer['billing_city'].', '. $customer['billing_state'] .', '.$customer['billing_country']}}</h5>
                </div>
            </div>
        </div>
        <div class="col-md-4 col-lg-4 col-xl-4">
            <div class="card pb-0 customer-detail-box">
                <h3 class="small-title">{{__('Shipping Info')}}</h3>
                <div class="p-4">
                    <h5 class="report-text gray-text mb-0">{{$customer['shipping_name']}}</h5>
                    <h5 class="report-text gray-text mb-0">{{$customer['shipping_phone']}}</h5>
                    <h5 class="report-text gray-text mb-0">{{$customer['shipping_address']}}</h5>
                    <h5 class="report-text gray-text mb-0">{{$customer['shipping_zip']}}</h5>
                    <h5 class="report-text gray-text mb-0">{{$customer['shipping_city'].', '. $customer['billing_state'] .', '.$customer['billing_country']}}</h5>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card pb-0">
                <h3 class="small-title">{{__('Company Info')}}</h3>
                <div class="row">
                    @php
                        $totalInvoiceSum=$customer->customerTotalInvoiceSum($customer['id']);
                        $totalInvoice=$customer->customerTotalInvoice($customer['id']);
                        $averageSale=($totalInvoiceSum!=0)?$totalInvoiceSum/$totalInvoice:0;
                    @endphp
                    <div class="col-md-3 col-sm-6">
                        <div class="p-4">
                            <h5 class="report-text gray-text mb-0">{{__('Customer Id')}}</h5>
                            <h5 class="report-text mb-3">{{AUth::user()->customerNumberFormat($customer['customer_id'])}}</h5>
                            <h5 class="report-text gray-text mb-0">{{__('Total Sum of Invoices')}}</h5>
                            <h5 class="report-text mb-0">{{\Auth::user()->priceFormat($totalInvoiceSum)}}</h5>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="p-4">
                            <h5 class="report-text gray-text mb-0">{{__('Date of Creation')}}</h5>
                            <h5 class="report-text mb-3">{{\Auth::user()->dateFormat($customer['created_at'])}}</h5>
                            <h5 class="report-text gray-text mb-0">{{__('Quantity of Invoice')}}</h5>
                            <h5 class="report-text mb-0">{{$totalInvoice}}</h5>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="p-4">
                            <h5 class="report-text gray-text mb-0">{{__('Balance')}}</h5>
                            <h5 class="report-text mb-3">{{\Auth::user()->priceFormat($customer['balance'])}}</h5>
                            <h5 class="report-text gray-text mb-0">{{__('Average Sales')}}</h5>
                            <h5 class="report-text mb-0">{{\Auth::user()->priceFormat($averageSale)}}</h5>
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6">
                        <div class="p-4">
                            <h5 class="report-text gray-text mb-0">{{__('Overdue')}}</h5>
                            <h5 class="report-text mb-3">{{\Auth::user()->priceFormat($customer->customerOverdue($customer['id']))}}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <h5 class="h4 d-inline-block font-weight-400 mb-4">{{__('Proposal')}}</h5>
            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr>
                                <th>{{__('Proposal')}}</th>
                                <th>{{__('Issue Date')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Status')}}</th>
                                @if(Gate::check('edit proposal') || Gate::check('delete proposal') || Gate::check('show proposal'))
                                    <th> {{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($customer->customerProposal($customer->id) as $proposal)
                                <tr>
                                    <td class="Id">
                                        @if(\Auth::guard('customer')->check())
                                            <a href="{{ route('customer.proposal.show',\Crypt::encrypt($proposal->id)) }}">{{ AUth::user()->proposalNumberFormat($proposal->proposal_id) }}
                                            </a>
                                        @else
                                            <a href="{{ route('proposal.show',\Crypt::encrypt($proposal->id)) }}">{{ AUth::user()->proposalNumberFormat($proposal->proposal_id) }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ Auth::user()->dateFormat($proposal->issue_date) }}</td>
                                    <td>{{ Auth::user()->priceFormat($proposal->getTotal()) }}</td>
                                    <td>
                                        @if($proposal->status == 0)
                                            <span class="badge badge-pill badge-primary">{{ __(\App\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 1)
                                            <span class="badge badge-pill badge-warning">{{ __(\App\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 2)
                                            <span class="badge badge-pill badge-danger">{{ __(\App\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 3)
                                            <span class="badge badge-pill badge-info">{{ __(\App\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 4)
                                            <span class="badge badge-pill badge-success">{{ __(\App\Proposal::$statues[$proposal->status]) }}</span>
                                        @endif
                                    </td>
                                    @if(Gate::check('edit proposal') || Gate::check('delete proposal') || Gate::check('show proposal'))
                                        <td class="Action">
                                            <span>
                                            @can('convert invoice')
                                                    <a href="#" class="edit-icon bg-yellow" data-toggle="tooltip" data-original-title="{{__('Convert to Invoice')}}" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="You want to confirm convert to invoice. Press Yes to continue or Cancel to go back" data-confirm-yes="document.getElementById('proposal-form-{{$proposal->id}}').submit();">
                                                    <i class="fas fa-exchange-alt"></i>
                                                    {!! Form::open(['method' => 'get', 'route' => ['proposal.convert', $proposal->id],'id'=>'proposal-form-'.$proposal->id]) !!}
                                                        {!! Form::close() !!}
                                                </a>
                                                @endcan
                                                @can('duplicate proposal')
                                                    <a href="#" class="edit-icon bg-success" data-toggle="tooltip" data-original-title="{{__('Duplicate')}}" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="You want to confirm duplicate this invoice. Press Yes to continue or Cancel to go back" data-confirm-yes="document.getElementById('duplicate-form-{{$proposal->id}}').submit();">
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
    <div class="row">
        <div class="col-12">
            <h5 class="h4 d-inline-block font-weight-400 mb-4">{{__('Invoice')}}</h5>
            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr>
                                <th>{{__('Invoice')}}</th>
                                <th>{{__('Issue Date')}}</th>
                                <th>{{__('Due Date')}}</th>
                                <th>{{__('Due Amount')}}</th>
                                <th>{{__('Status')}}</th>
                                @if(Gate::check('edit invoice') || Gate::check('delete invoice') || Gate::check('show invoice'))
                                    <th> {{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($customer->customerInvoice($customer->id) as $invoice)
                                <tr>
                                    <td class="Id">
                                        @if(\Auth::guard('customer')->check())
                                            <a href="{{ route('customer.invoice.show',\Crypt::encrypt($invoice->id)) }}">{{ AUth::user()->invoiceNumberFormat($invoice->invoice_id) }}
                                            </a>
                                        @else
                                            <a href="{{ route('invoice.show',\Crypt::encrypt($invoice->id)) }}">{{ AUth::user()->invoiceNumberFormat($invoice->invoice_id) }}
                                            </a>
                                        @endif
                                    </td>
                                    <td>{{ \Auth::user()->dateFormat($invoice->issue_date) }}</td>
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
                                            <span>
                                            @can('duplicate invoice')
                                                    <a href="#" class="edit-icon bg-success" data-toggle="tooltip" data-original-title="{{__('Duplicate')}}" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="You want to confirm this action. Press Yes to continue or Cancel to go back" data-confirm-yes="document.getElementById('duplicate-form-{{$invoice->id}}').submit();">
                                                    <i class="fas fa-copy"></i>
                                                    {!! Form::open(['method' => 'get', 'route' => ['invoice.duplicate', $invoice->id],'id'=>'duplicate-form-'.$invoice->id]) !!}
                                                        {!! Form::close() !!}
                                                </a>
                                                @endcan
                                                @can('show invoice')
                                                    @if(\Auth::guard('customer')->check())
                                                        <a href="{{ route('customer.invoice.show',\Crypt::encrypt($invoice->id)) }}" class="edit-icon bg-info" data-toggle="tooltip" data-original-title="{{__('Detail')}}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @else
                                                        <a href="{{ route('invoice.show',\Crypt::encrypt($invoice->id)) }}" class="edit-icon bg-info" data-toggle="tooltip" data-original-title="{{__('Detail')}}">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    @endif
                                                @endcan
                                                @can('edit invoice')
                                                    <a href="{{ route('invoice.edit',\Crypt::encrypt($invoice->id)) }}" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
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
