@extends('layouts.admin')
@section('page-title')
    {{__('Bill Detail')}}
@endsection
@push('script-page')
    <script>
        $(document).on('click', '#shipping', function () {
            var url = $(this).data('url');
            var is_display = $("#shipping").is(":checked");
            $.ajax({
                url: url,
                type: 'get',
                data: {
                    'is_display': is_display,
                },
                success: function (data) {
                }
            });
        })
    </script>
@endpush
@section('content')
    @can('send bill')
        @if($bill->status!=4)
            <div class="row">
                <div class="col-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                                <div class="timeline-block">
                                    <span class="timeline-step timeline-step-sm bg-primary border-primary text-white"><i class="fas fa-plus"></i></span>
                                    <div class="timeline-content">
                                        <div class="text-sm h6">{{__('Create Bill')}}</div>
                                        @can('edit bill')
                                            <div class="Action">
                                                <a href="{{ route('bill.edit',\Crypt::encrypt($bill->id)) }}" class="edit-icon float-right" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="fas fa-pencil-alt"></i></a>
                                            </div>
                                        @endcan
                                        <small><i class="fas fa-clock mr-1"></i>{{__('Created on ')}} {{\Auth::user()->dateFormat($bill->bill_date)}}</small>
                                    </div>
                                </div>
                                <div class="timeline-block">
                                    <span class="timeline-step timeline-step-sm bg-warning border-warning text-white"><i class="fas fa-envelope"></i></span>
                                    <div class="timeline-content">
                                        <div class="text-sm h6 ">{{__('Send Bill')}}</div>
                                        @if($bill->status==0)
                                            <div class="Action">
                                                @can('send bill')
                                                    <a href="{{ route('bill.sent',$bill->id) }}" class="send-icon float-right" data-toggle="tooltip" data-original-title="{{__('Mark Sent')}}"><i class="fa fa-paper-plane"></i></a>
                                                @endcan
                                            </div>
                                        @endif
                                        @if($bill->status!=0)
                                            <small><i class="fas fa-clock mr-1"></i>{{__('Sent on')}} {{\Auth::user()->dateFormat($bill->send_date)}}</small>
                                        @else
                                            @can('send bill')
                                                <small>{{__('Status')}} : {{__('Not Sent')}}</small>
                                            @endcan
                                        @endif
                                    </div>
                                </div>
                                <div class="timeline-block">
                                    <span class="timeline-step timeline-step-sm bg-info border-info text-white"><i class="far fa-money-bill-alt"></i></span>
                                    <div class="timeline-content">
                                        <span class="text-sm h6 ">{{__('Get Paid')}}</span>
                                        @if($bill->status!=0)
                                            @can('create payment bill')
                                                <a href="#" data-url="{{ route('bill.payment',$bill->id) }}" data-ajax-popup="true" data-title="{{__('Add Payment')}}" class="edit-icon float-right" data-toggle="tooltip" data-original-title="{{__('Add Payment')}}"><i class="fas fa-money-bill-alt"></i></a> <br>
                                            @endcan
                                        @endif
                                        <small>{{__('Status')}} : {{__('Awaiting payment')}}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endcan

    @if(\Auth::user()->type=='company')
        @if($bill->status!=0)
            <div class="row justify-content-between align-items-center mb-3">
                <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                    @if(!empty($billPayment))
                        <div class="all-button-box mx-2">
                            <a href="#" data-url="{{ route('bill.debit.note',$bill->id) }}" data-ajax-popup="true" data-title="{{__('Add Debit Note')}}" class="btn btn-xs btn-white btn-icon-only width-auto">
                                {{__('Add Debit Note')}}
                            </a>
                        </div>
                    @endif
                    <div class="all-button-box mx-2">
                        <a href="{{ route('bill.resent',$bill->id) }}" class="btn btn-xs btn-white btn-icon-only width-auto">
                            {{__('Resend Bill')}}
                        </a>
                    </div>
                    <div class="all-button-box">
                        <a href="{{ route('bill.pdf', \Crypt::encrypt($bill->id))}}" target="_blank" class="btn btn-xs btn-white btn-icon-only width-auto">
                            {{__('Download')}}
                        </a>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="row justify-content-between align-items-center mb-3">
            <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                <div class="all-button-box mx-2">
                    <a href="#" data-url="{{route('vender.bill.send',$bill->id)}}" data-ajax-popup="true" data-title="{{__('Send Bill')}}" class="btn btn-xs btn-white btn-icon-only width-auto">
                        {{__('Send Mail')}}
                    </a>
                </div>
                <div class="all-button-box mx-2">
                    <a href="{{ route('bill.pdf', \Crypt::encrypt($bill->id))}}" target="_blank" class="btn btn-xs btn-white btn-icon-only width-auto">
                        {{__('Download')}}
                    </a>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row invoice-title mt-2">
                                <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                                    <h2>{{__('Bill')}}</h2>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-right">
                                    <h3 class="invoice-number">{{ Auth::user()->billNumberFormat($bill->bill_id) }}</h3>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                @if(!empty($vendor->billing_name))
                                    <div class="col-md-6">
                                        <small class="font-style">
                                            <strong>{{__('Billed To')}} :</strong><br>
                                            {{!empty($vendor->billing_name)?$vendor->billing_name:''}}<br>
                                            {{!empty($vendor->billing_phone)?$vendor->billing_phone:''}}<br>
                                            {{!empty($vendor->billing_address)?$vendor->billing_address:''}}<br>
                                            {{!empty($vendor->billing_zip)?$vendor->billing_zip:''}}<br>
                                            {{!empty($vendor->billing_city)?$vendor->billing_city:'' .', '}} {{!empty($vendor->billing_state)?$vendor->billing_state:'',', '}} {{!empty($vendor->billing_country)?$vendor->billing_country:''}}
                                        </small>
                                    </div>
                                @endif
                                @if(\Utility::getValByName('shipping_display')=='on')
                                    <div class="col-md-6 text-md-right">
                                        <small>
                                            <strong>{{__('Shipped To')}} :</strong><br>
                                            {{!empty($vendor->shipping_name)?$vendor->shipping_name:''}}<br>
                                            {{!empty($vendor->shipping_phone)?$vendor->shipping_phone:''}}<br>
                                            {{!empty($vendor->shipping_address)?$vendor->shipping_address:''}}<br>
                                            {{!empty($vendor->shipping_zip)?$vendor->shipping_zip:''}}<br>
                                            {{!empty($vendor->shipping_city)?$vendor->shipping_city:'' .', '}} {{!empty($vendor->shipping_state)?$vendor->shipping_state:'',', '}} {{!empty($vendor->shipping_country)?$vendor->shipping_country:''}}
                                        </small>
                                    </div>
                                @endif
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <small>
                                        <strong>{{__('Status')}} :</strong><br>
                                        @if($bill->status == 0)
                                            <span class="badge badge-pill badge-primary">{{ __(\App\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 1)
                                            <span class="badge badge-pill badge-warning">{{ __(\App\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 2)
                                            <span class="badge badge-pill badge-danger">{{ __(\App\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 3)
                                            <span class="badge badge-pill badge-info">{{ __(\App\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 4)
                                            <span class="badge badge-pill badge-success">{{ __(\App\Bill::$statues[$bill->status]) }}</span>
                                        @endif
                                    </small>
                                </div>
                                <div class="col text-md-center">
                                    <small>
                                        <strong>{{__('Issue Date')}} :</strong><br>
                                        {{\Auth::user()->dateFormat($bill->bill_date)}}<br><br>
                                    </small>
                                </div>
                                <div class="col text-md-right">
                                    <small>
                                        <strong>{{__('Due Date')}} :</strong><br>
                                        {{\Auth::user()->dateFormat($bill->due_date)}}<br><br>
                                    </small>
                                </div>

                                @if(!empty($customFields) && count($bill->customField)>0)
                                    @foreach($customFields as $field)
                                        <div class="col text-md-right">
                                            <small>
                                                <strong>{{$field->name}} :</strong><br>
                                                {{!empty($bill->customField)?$bill->customField[$field->id]:'-'}}
                                                <br><br>
                                            </small>
                                        </div>
                                    @endforeach
                                @endif
                            </div>

                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="font-weight-bold">{{__('Product Summary')}}</div>
                                    <small>{{__('All items here cannot be deleted.')}}</small>
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <tr>
                                                <th class="text-dark" data-width="40">#</th>
                                                <th class="text-dark">{{__('Product')}}</th>
                                                <th class="text-dark">{{__('Quantity')}}</th>
                                                <th class="text-dark">{{__('Rate')}}</th>
                                                <th class="text-dark">{{__('Tax')}}</th>
                                                <th class="text-dark">  @if($bill->discount_apply==1)
                                                        {{__('Discount')}}
                                                    @endif
                                                </th>
                                                <th class="text-dark">{{__('Description')}}</th>
                                                <th class="text-right text-dark" width="12%">{{__('Price')}}<br>
                                                    <small class="text-danger font-weight-bold">{{__('before tax & discount')}}</small>
                                                </th>
                                            </tr>
                                            @php
                                                $totalQuantity=0;
                                                $totalRate=0;
                                                $totalTaxPrice=0;
                                                $totalDiscount=0;
                                                $taxesData=[];
                                            @endphp

                                            @foreach($iteams as $key =>$iteam)
                                                @if(!empty($iteam->tax))
                                                    @php
                                                        $taxes=\Utility::tax($iteam->tax);
                                                        $totalQuantity+=$iteam->quantity;
                                                        $totalRate+=$iteam->price;
                                                        $totalDiscount+=$iteam->discount;
                                                        foreach($taxes as $taxe){
                                                            $taxDataPrice=\Utility::taxRate($taxe->rate,$iteam->price,$iteam->quantity);
                                                            if (array_key_exists($taxe->name,$taxesData))
                                                            {
                                                                $taxesData[$taxe->name] = $taxesData[$taxe->name]+$taxDataPrice;
                                                            }
                                                            else
                                                            {
                                                                $taxesData[$taxe->name] = $taxDataPrice;
                                                            }
                                                        }
                                                    @endphp
                                                @endif
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{!empty($iteam->product())?$iteam->product()->name:''}}</td>
                                                    <td>{{$iteam->quantity}}</td>
                                                    <td>{{\Auth::user()->priceFormat($iteam->price)}}</td>
                                                    <td>
                                                        @if(!empty($iteam->tax))
                                                            <table>
                                                                @php $totalTaxRate = 0;@endphp
                                                                @foreach($taxes as $tax)
                                                                    @php
                                                                        $taxPrice=\Utility::taxRate($tax->rate,$iteam->price,$iteam->quantity);
                                                                        $totalTaxPrice+=$taxPrice;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{$tax->name .' ('.$tax->rate .'%)'}}</td>
                                                                        <td>{{\Auth::user()->priceFormat($taxPrice)}}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </table>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>  @if($bill->discount_apply==1)
                                                            {{\Auth::user()->priceFormat($iteam->discount)}}
                                                        @endif
                                                    </td>
                                                    <td>{{!empty($iteam->description)?$iteam->description:'-'}}</td>
                                                    <td class="text-right">{{\Auth::user()->priceFormat(($iteam->price*$iteam->quantity))}}</td>
                                                </tr>
                                            @endforeach
                                            <tfoot>
                                            <tr>
                                                <td></td>
                                                <td><b>{{__('Total')}}</b></td>
                                                <td><b>{{$totalQuantity}}</b></td>
                                                <td><b>{{\Auth::user()->priceFormat($totalRate)}}</b></td>
                                                <td><b>{{\Auth::user()->priceFormat($totalTaxPrice)}}</b></td>
                                                <td> @if($bill->discount_apply==1)
                                                        <b>{{\Auth::user()->priceFormat($totalDiscount)}}</b>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"></td>
                                                <td class="text-right"><b>{{__('Sub Total')}}</b></td>
                                                <td class="text-right">{{\Auth::user()->priceFormat($bill->getSubTotal())}}</td>
                                            </tr>
                                            @if($bill->discount_apply==1)
                                                <tr>
                                                    <td colspan="5"></td>
                                                    <td class="text-right"><b>{{__('Discount')}}</b></td>
                                                    <td class="text-right">{{\Auth::user()->priceFormat($bill->getTotalDiscount())}}</td>
                                                </tr>
                                            @endif
                                            @if(!empty($taxesData))
                                                @foreach($taxesData as $taxName => $taxPrice)
                                                    <tr>
                                                        <td colspan="5"></td>
                                                        <td class="text-right"><b>{{$taxName}}</b></td>
                                                        <td class="text-right">{{ \Auth::user()->priceFormat($taxPrice) }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            <tr>
                                                <td colspan="5"></td>
                                                <td class="blue-text text-right"><b>{{__('Total')}}</b></td>
                                                <td class="blue-text text-right">{{\Auth::user()->priceFormat($bill->getTotal())}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"></td>
                                                <td class="text-right"><b>{{__('Paid')}}</b></td>
                                                <td class="text-right">{{\Auth::user()->priceFormat(($bill->getTotal()-$bill->getDue())-($bill->billTotalDebitNote()))}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"></td>
                                                <td class="text-right"><b>{{__('Debit Note')}}</b></td>
                                                <td class="text-right">{{\Auth::user()->priceFormat(($bill->billTotalDebitNote()))}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="5"></td>
                                                <td class="text-right"><b>{{__('Due')}}</b></td>
                                                <td class="text-right">{{\Auth::user()->priceFormat($bill->getDue())}}</td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <h5 class="h4 d-inline-block font-weight-400 mb-4">{{__('Payment Summary')}}</h5>
            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th class="text-dark">{{__('Date')}}</th>
                                <th class="text-dark">{{__('Amount')}}</th>
                                <th class="text-dark">{{__('Account')}}</th>
                                <th class="text-dark">{{__('Reference')}}</th>
                                <th class="text-dark">{{__('Description')}}</th>
                                @can('delete payment bill')
                                    <th class="text-dark">{{__('Action')}}</th>
                                @endcan
                            </tr>
                            @forelse($bill->payments as $key =>$payment)
                                <tr>
                                    <td>{{\Auth::user()->dateFormat($payment->date)}}</td>
                                    <td>{{\Auth::user()->priceFormat($payment->amount)}}</td>
                                    <td>{{!empty($payment->bankAccount)?$payment->bankAccount->bank_name.' '.$payment->bankAccount->holder_name:''}}</td>
                                    <td>{{$payment->reference}}</td>
                                    <td>{{$payment->description}}</td>
                                    <td class="text-dark">
                                        @can('delete bill product')
                                            <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$payment->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'post', 'route' => ['bill.payment.destroy',$bill->id,$payment->id],'id'=>'delete-form-'.$payment->id]) !!}
                                            {!! Form::close() !!}
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-dark"><p>{{__('No Data Found')}}</p></td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <h5 class="h4 d-inline-block font-weight-400 mb-4">{{__('Debit Note Summary')}}</h5>
            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th class="text-dark">{{__('Date')}}</th>
                                <th class="text-dark">{{__('Amount')}}</th>
                                <th class="text-dark">{{__('Description')}}</th>
                                @if(Gate::check('edit debit note') || Gate::check('delete debit note'))
                                    <th class="text-dark">{{__('Action')}}</th>
                                @endif
                            </tr>
                            @forelse($bill->debitNote as $key =>$debitNote)
                                <tr>
                                    <td>{{\Auth::user()->dateFormat($debitNote->date)}}</td>
                                    <td>{{\Auth::user()->priceFormat($debitNote->amount)}}</td>
                                    <td>{{$debitNote->description}}</td>
                                    <td>
                                        @can('edit debit note')
                                            <a data-url="{{ route('bill.edit.debit.note',[$debitNote->bill,$debitNote->id]) }}" data-ajax-popup="true" data-title="{{__('Add Debit Note')}}" href="#" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        @endcan
                                        @can('delete debit note')
                                            <a href="#" class="delete-icon " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$debitNote->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => array('bill.delete.debit.note', $debitNote->bill,$debitNote->id),'id'=>'delete-form-'.$debitNote->id]) !!}
                                            {!! Form::close() !!}
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-dark"><p>{{__('No Data Found')}}</p></td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
