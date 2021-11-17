@extends('layouts.admin')
@section('page-title')
    {{__('Invoice Detail')}}
@endsection
@push('css-page')
    <style>
        #card-element {
            border: 1px solid #a3afbb !important;
            border-radius: 10px !important;
            padding: 10px !important;
        }
    </style>
@endpush
@push('script-page')
    <script src="https://js.stripe.com/v3/"></script>
    <script type="text/javascript">
            @if($invoice->getDue() > 0 &&  Utility::getValByName('enable_stripe') == 'on' && !empty(Utility::getValByName('stripe_key')) && !empty(Utility::getValByName('stripe_secret')))
        var stripe = Stripe('{{ Utility::getValByName('stripe_key') }}');
        var elements = stripe.elements();

        // Custom styling can be passed to options when creating an Element.
        var style = {
            base: {
                // Add your base input styles here. For example:
                fontSize: '14px',
                color: '#32325d',
            },
        };

        // Create an instance of the card Element.
        var card = elements.create('card', {style: style});

        // Add an instance of the card Element into the `card-element` <div>.
        card.mount('#card-element');

        // Create a token or display an error when the form is submitted.
        var form = document.getElementById('payment-form');
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            stripe.createToken(card).then(function (result) {
                if (result.error) {
                    $("#card-errors").html(result.error.message);
                    show_toastr('Error', result.error.message, 'error');
                } else {
                    // Send the token to your server.
                    stripeTokenHandler(result.token);
                }
            });
        });

        function stripeTokenHandler(token) {
            // Insert the token ID into the form so it gets submitted to the server
            var form = document.getElementById('payment-form');
            var hiddenInput = document.createElement('input');
            hiddenInput.setAttribute('type', 'hidden');
            hiddenInput.setAttribute('name', 'stripeToken');
            hiddenInput.setAttribute('value', token.id);
            form.appendChild(hiddenInput);

            // Submit the form
            form.submit();
        }

        @endif


    </script>
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
    @can('send invoice')
        @if($invoice->status!=4)
            <div class="row">
                <div class="col-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                                <div class="timeline-block">
                                    <span class="timeline-step timeline-step-sm bg-primary border-primary text-white"><i class="fas fa-plus"></i></span>
                                    <div class="timeline-content">
                                        <div class="text-sm h6">{{__('Create Invoice')}}</div>
                                        @can('edit invoice')
                                            <div class="Action">
                                                <a href="{{ route('invoice.edit',\Crypt::encrypt($invoice->id)) }}" class="edit-icon float-right" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="fas fa-pencil-alt"></i></a>
                                            </div>
                                        @endcan
                                        <small><i class="fas fa-clock mr-1"></i>{{__('Created on ')}} {{$user->dateFormat($invoice->issue_date)}}</small>
                                    </div>
                                </div>
                                <div class="timeline-block">
                                    <span class="timeline-step timeline-step-sm bg-warning border-warning text-white"><i class="fas fa-envelope"></i></span>
                                    <div class="timeline-content">
                                        <div class="text-sm h6 ">{{__('Send Invoice')}}</div>
                                        @if($invoice->status==0)
                                            <div class="Action">
                                                @can('send invoice')
                                                    <a href="{{ route('invoice.sent',$invoice->id) }}" class="send-icon float-right" data-toggle="tooltip" data-original-title="{{__('Mark Sent')}}"><i class="fa fa-paper-plane"></i></a>
                                                @endcan
                                            </div>
                                        @endif

                                        @if($invoice->status!=0)
                                            <small><i class="fas fa-clock mr-1"></i>{{__('Sent on')}} {{$user->dateFormat($invoice->send_date)}}</small>
                                        @else
                                            @can('send invoice')
                                                <small>{{__('Status')}} : {{__('Not Sent')}}</small>
                                            @endcan
                                        @endif
                                    </div>
                                </div>
                                <div class="timeline-block">
                                    <span class="timeline-step timeline-step-sm bg-info border-info text-white"><i class="far fa-money-bill-alt"></i></span>
                                    <div class="timeline-content">
                                        <div class="text-sm h6 ">{{__('Get Paid')}}</div>
                                        @if($invoice->status!=0)
                                            @can('create payment invoice')
                                                <a href="#" data-url="{{ route('invoice.payment',$invoice->id) }}" data-ajax-popup="true" data-title="{{__('Add Receipt')}}" class="edit-icon float-right" data-toggle="tooltip" data-original-title="{{__('Add Receipt')}}"><i class="far fa-file"></i></a>
                                            @endcan
                                        @endif
                                        <small>{{__('Awaiting payment')}}</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endcan

    @if($user->type=='company')
        @if($invoice->status!=0)
            <div class="row justify-content-between align-items-center mb-3">
                <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                    @if(!empty($invoicePayment))
                        <div class="all-button-box mx-2">
                            <a href="#" class="btn btn-xs btn-white btn-icon-only width-auto" data-url="{{ route('invoice.credit.note',$invoice->id) }}" data-ajax-popup="true" data-title="{{__('Add Credit Note')}}">
                                {{__('Add Credit Note')}}
                            </a>
                        </div>
                    @endif
                    @if($invoice->status!=4)
                        <div class="all-button-box mx-2">
                            <a href="{{ route('invoice.payment.reminder',$invoice->id)}}" class="btn btn-xs btn-white btn-icon-only width-auto">{{__('Receipt Reminder')}}</a>
                        </div>
                    @endif
                    <div class="all-button-box mx-2">
                        <a href="{{ route('invoice.resent',$invoice->id)}}" class="btn btn-xs btn-white btn-icon-only width-auto">{{__('Resend Invoice')}}</a>
                    </div>
                    <div class="all-button-box">
                        <a href="{{ route('invoice.pdf', Crypt::encrypt($invoice->id))}}" target="_blank" class="btn btn-xs btn-white btn-icon-only width-auto">{{__('Download')}}</a>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="row justify-content-between align-items-center mb-3">
            <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                <div class="all-button-box mx-2">
                    <a href="#" class="btn btn-xs btn-white btn-icon-only width-auto" data-url="{{route('customer.invoice.send',$invoice->id)}}" data-ajax-popup="true" data-title="{{__('Send Invoice')}}">
                        {{__('Send Mail')}}
                    </a>
                </div>
                <div class="all-button-box mx-2">
                    <a href="{{ route('invoice.pdf', Crypt::encrypt($invoice->id))}}" target="_blank" class="btn btn-xs btn-white btn-icon-only width-auto">
                        {{__('Download')}}
                    </a>
                </div>

                @if($invoice->status!=0 && $invoice->getDue() > 0 && ((Utility::getValByName('enable_stripe') == 'on' && !empty(Utility::getValByName('stripe_key')) && !empty(Utility::getValByName('stripe_secret'))) || (Utility::getValByName('enable_paypal')== 'on' && !empty(Utility::getValByName('paypal_client_id')) && !empty(Utility::getValByName('paypal_secret_key')))))
                    <div class="all-button-box">
                        <a href="#" class="btn btn-xs btn-white btn-icon-only width-auto" data-toggle="modal" data-target="#paymentModal">
                            {{__('Pay Now')}}
                        </a>
                    </div>
                @endif
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
                                    <h2>{{__('Invoice')}}</h2>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-right">
                                    <h3 class="invoice-number">{{ $user->invoiceNumberFormat($invoice->invoice_id) }}</h3>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                @if(!empty($customer->billing_name))
                                    <div class="col-md-6">
                                        <small class="font-style">
                                            <strong>{{__('Billed To')}} :</strong><br>
                                            {{!empty($customer->billing_name)?$customer->billing_name:''}}<br>
                                            {{!empty($customer->billing_phone)?$customer->billing_phone:''}}<br>
                                            {{!empty($customer->billing_address)?$customer->billing_address:''}}<br>
                                            {{!empty($customer->billing_zip)?$customer->billing_zip:''}}<br>
                                            {{!empty($customer->billing_city)?$customer->billing_city:'' .', '}} {{!empty($customer->billing_state)?$customer->billing_state:'',', '}} {{!empty($customer->billing_country)?$customer->billing_country:''}}
                                        </small>
                                    </div>
                                @endif
                                @if(\Utility::getValByName('shipping_display')=='on')
                                    <div class="col-md-6 text-md-right">
                                        <small>
                                            <strong>{{__('Shipped To')}} :</strong><br>
                                            {{!empty($customer->shipping_name)?$customer->shipping_name:''}}<br>
                                            {{!empty($customer->shipping_phone)?$customer->shipping_phone:''}}<br>
                                            {{!empty($customer->shipping_address)?$customer->shipping_address:''}}<br>
                                            {{!empty($customer->shipping_zip)?$customer->shipping_zip:''}}<br>
                                            {{!empty($customer->shipping_city)?$customer->shipping_city:'' . ', '}} {{!empty($customer->shipping_state)?$customer->shipping_state:'' .', '}},{{!empty($customer->shipping_country)?$customer->shipping_country:''}}
                                        </small>
                                    </div>
                                @endif
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <small>
                                        <strong>{{__('Status')}} :</strong><br>
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
                                    </small>
                                </div>
                                <div class="col text-md-center">
                                    <small>
                                        <strong>{{__('Issue Date')}} :</strong><br>
                                        {{$user->dateFormat($invoice->issue_date)}}<br><br>
                                    </small>
                                </div>
                                <div class="col text-md-right">
                                    <small>
                                        <strong>{{__('Due Date')}} :</strong><br>
                                        {{$user->dateFormat($invoice->due_date)}}<br><br>
                                    </small>
                                </div>

                                @if(!empty($customFields) && count($invoice->customField)>0)
                                    @foreach($customFields as $field)
                                        <div class="col text-md-right">
                                            <small>
                                                <strong>{{$field->name}} :</strong><br>
                                                {{!empty($invoice->customField)?$invoice->customField[$field->id]:'-'}}
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
                                    <div class="table-responsive mt-2">
                                        <table class="table mb-0 table-striped">
                                            <tr>
                                                <th data-width="40" class="text-dark">#</th>
                                                <th class="text-dark">{{__('Product')}}</th>
                                                <th class="text-dark">{{__('Quantity')}}</th>
                                                <th class="text-dark">{{__('Rate')}}</th>
                                                <th class="text-dark">{{__('Tax')}}</th>
                                                <th class="text-dark">@if($invoice->discount_apply==1){{__('Discount')}}@endif</th>
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
                                                    <td>{{$user->priceFormat($iteam->price)}}</td>
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
                                                                        <td>{{$user->priceFormat($taxPrice)}}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </table>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td> @if($invoice->discount_apply==1)
                                                            {{$user->priceFormat($iteam->discount)}}
                                                        @endif
                                                    </td>
                                                    <td>{{!empty($iteam->description)?$iteam->description:'-'}}</td>
                                                    <td class="text-right">{{$user->priceFormat(($iteam->price*$iteam->quantity))}}</td>
                                                </tr>
                                            @endforeach
                                            <tfoot>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td><b>{{__('Total')}}</b></td>
                                                <td><b>{{$totalQuantity}}</b></td>
                                                <td><b>{{$user->priceFormat($totalRate)}}</b></td>
                                                <td><b>{{$user->priceFormat($totalTaxPrice)}}</b></td>
                                                <td>  @if($invoice->discount_apply==1)
                                                        <b>{{$user->priceFormat($totalDiscount)}}</b>
                                                    @endif
                                                </td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6"></td>
                                                <td class="text-right"><b>{{__('Sub Total')}}</b></td>
                                                <td class="text-right">{{$user->priceFormat($invoice->getSubTotal())}}</td>
                                            </tr>
                                            @if($invoice->discount_apply==1)
                                                <tr>
                                                    <td colspan="6"></td>
                                                    <td class="text-right"><b>{{__('Discount')}}</b></td>
                                                    <td class="text-right">{{$user->priceFormat($invoice->getTotalDiscount())}}</td>
                                                </tr>
                                            @endif
                                            @if(!empty($taxesData))
                                                @foreach($taxesData as $taxName => $taxPrice)
                                                    <tr>
                                                        <td colspan="6"></td>
                                                        <td class="text-right"><b>{{$taxName}}</b></td>
                                                        <td class="text-right">{{ $user->priceFormat($taxPrice) }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            <tr>
                                                <td colspan="6"></td>
                                                <td class="blue-text text-right"><b>{{__('Total')}}</b></td>
                                                <td class="blue-text text-right">{{$user->priceFormat($invoice->getTotal())}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="6"></td>
                                                <td class="text-right"><b>{{__('Paid')}}</b></td>
                                                <td class="text-right">{{$user->priceFormat(($invoice->getTotal()-$invoice->getDue())-($invoice->invoiceTotalCreditNote()))}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="6"></td>
                                                <td class="text-right"><b>{{__('Credit Note')}}</b></td>
                                                <td class="text-right">{{$user->priceFormat(($invoice->invoiceTotalCreditNote()))}}</td>
                                            </tr>
                                            <tr>
                                                <td colspan="6"></td>
                                                <td class="text-right"><b>{{__('Due')}}</b></td>
                                                <td class="text-right">{{$user->priceFormat($invoice->getDue())}}</td>
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
            <h5 class="h4 d-inline-block font-weight-400 mb-4">{{__('Receipt Summary')}}</h5>
            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th class="text-dark">{{__('Date')}}</th>
                                <th class="text-dark">{{__('Amount')}}</th>
                                <th class="text-dark">{{__('Payment Type')}}</th>
                                <th class="text-dark">{{__('Account')}}</th>
                                <th class="text-dark">{{__('Reference')}}</th>
                                <th class="text-dark">{{__('Description')}}</th>
                                <th class="text-dark">{{__('Receipt')}}</th>
                                <th class="text-dark">{{__('OrderId')}}</th>
                                @can('delete payment invoice')
                                    <th class="text-dark">{{__('Action')}}</th>
                                @endcan
                            </tr>
                            @forelse($invoice->payments as $key =>$payment)
                                <tr>
                                    <td>{{$user->dateFormat($payment->date)}}</td>
                                    <td>{{$user->priceFormat($payment->amount)}}</td>
                                    <td>{{$payment->payment_type}}</td>
                                    <td>{{!empty($payment->bankAccount)?$payment->bankAccount->bank_name.' '.$payment->bankAccount->holder_name:'--'}}</td>
                                    <td>{{!empty($payment->reference)?$payment->reference:'--'}}</td>
                                    <td>{{!empty($payment->description)?$payment->description:'--'}}</td>
                                    <td>@if(!empty($payment->receipt))<a href="{{$payment->receipt}}" target="_blank"> <i class="fas fa-file"></i></a>@else -- @endif</td>
                                    <td>{{!empty($payment->order_id)?$payment->order_id:'--'}}</td>
                                    @can('delete invoice product')
                                        <td>
                                            <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$payment->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'post', 'route' => ['invoice.payment.destroy',$invoice->id,$payment->id],'id'=>'delete-form-'.$payment->id]) !!}
                                            {!! Form::close() !!}
                                        </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ (Gate::check('delete invoice product') ? '9' : '8') }}" class="text-center text-dark"><p>{{__('No Data Found')}}</p></td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12">
            <h5 class="h4 d-inline-block font-weight-400 mb-4">{{__('Credit Note Summary')}}</h5>
            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <tr>
                                <th class="text-dark">{{__('Date')}}</th>
                                <th class="text-dark" class="text-center">{{__('Amount')}}</th>
                                <th class="text-dark" class="text-center">{{__('Description')}}</th>
                                @if(Gate::check('edit credit note') || Gate::check('delete credit note'))
                                    <th class="text-dark">{{__('Action')}}</th>
                                @endif
                            </tr>
                            @forelse($invoice->creditNote as $key =>$creditNote)
                                <tr>
                                    <td>{{$user->dateFormat($creditNote->date)}}</td>
                                    <td class="text-center">{{$user->priceFormat($creditNote->amount)}}</td>
                                    <td class="text-center">{{$creditNote->description}}</td>
                                    <td>
                                        @can('edit credit note')
                                            <a data-url="{{ route('invoice.edit.credit.note',[$creditNote->invoice,$creditNote->id]) }}" data-ajax-popup="true" data-title="{{__('Add Credit Note')}}" data-toggle="tooltip" data-original-title="{{__('Credit Note')}}" href="#" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        @endcan
                                        @can('delete credit note')
                                            <a href="#" class="delete-icon " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$creditNote->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => array('invoice.delete.credit.note', $creditNote->invoice,$creditNote->id),'id'=>'delete-form-'.$creditNote->id]) !!}
                                            {!! Form::close() !!}
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center">
                                        <p class="text-dark">{{__('No Data Found')}}</p>
                                    </td>
                                </tr>
                            @endforelse
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @auth('customer')
        @if($invoice->getDue() > 0)
            <div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="paymentModalLabel">{{ __('Add Payment') }}</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="card bg-none card-box">
                                <section class="nav-tabs p-2">
                                    @if(Utility::getValByName('enable_stripe') == 'on' && Utility::getValByName('enable_paypal') == 'on')
                                        <div class="row mb-3">
                                            <ul class="nav nav-tabs ml-2">
                                                @if(Utility::getValByName('enable_stripe') == 'on')
                                                    <li>
                                                        <a data-toggle="tab" class="active" id="contact-tab4" href="#stripe_payment">{{__('Stripe')}}</a>
                                                    </li>
                                                @endif
                                                @if(Utility::getValByName('enable_paypal') == 'on')
                                                    <li>
                                                        <a data-toggle="tab" id="contact-tab5" href="#paypal_payment">{{__('Paypal')}}</a>
                                                    </li>
                                                @endif
                                            </ul>
                                        </div>
                                    @endif
                                    <div class="tab-content">
                                        @if(Utility::getValByName('enable_stripe') == 'on')
                                            <div class="tab-pane {{ ((Utility::getValByName('enable_stripe') == 'on' && Utility::getValByName('enable_paypal') == 'on') || Utility::getValByName('enable_stripe') == 'on') ? "show active" : "" }}" id="stripe_payment">
                                                <form method="post" action="{{ route('customer.invoice.payment',$invoice->id) }}" class="require-validation">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <div class="custom-radio">
                                                                <label class="font-16 font-weight-bold">{{__('Credit / Debit Card')}}</label>
                                                            </div>
                                                            <small>{{__('Safe money transfer using your bank account. We support Mastercard, Visa, Discover and American express.')}}</small>
                                                        </div>
                                                        <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
                                                            <img src="{{asset('assets/img/payments/master.png')}}" height="24" alt="master-card-img">
                                                            <img src="{{asset('assets/img/payments/discover.png')}}" height="24" alt="discover-card-img">
                                                            <img src="{{asset('assets/img/payments/visa.png')}}" height="24" alt="visa-card-img">
                                                            <img src="{{asset('assets/img/payments/american express.png')}}" height="24" alt="american-express-card-img">
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="card-name-on" class="form-control-label">{{__('Name on card')}}</label>
                                                                <input type="text" name="name" id="card-name-on" class="form-control required" placeholder="{{$user->name}}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <div id="card-element">
                                                            </div>
                                                            <div id="card-errors" role="alert"></div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <br>
                                                            <label for="amount" class="form-control-label">{{ __('Amount') }}</label>
                                                            <div class="input-group">
                                                                <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-12">
                                                            <div class="error" style="display: none;">
                                                                <div class='alert-danger alert'>{{__('Please correct the errors and try again.')}}</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mt-3">
                                                        <input type="submit" value="{{__('Make Payment')}}" class="btn-create badge-blue">
                                                    </div>
                                                </form>
                                            </div>
                                        @endif
                                        @if(Utility::getValByName('enable_stripe') == 'on')
                                            <div class="tab-pane fade {{ (Utility::getValByName('enable_stripe') != 'on' && Utility::getValByName('enable_paypal') == 'on') ? "active" : "" }}" id="paypal_payment">
                                                <form method="post" action="{{ route('customer.pay.with.paypal',$invoice->id) }}" class="require-validation">
                                                    @csrf
                                                    <div class="row">
                                                        <div class="col-sm-8">
                                                            <div class="custom-radio">
                                                                <label class="font-16 font-weight-bold">{{__('Credit / Debit Card')}}</label>
                                                            </div>
                                                            <small>{{__('Safe money transfer using your bank account. We support Mastercard, Visa, Discover and American express.')}}</small>
                                                        </div>
                                                        <div class="col-sm-4 text-sm-right mt-3 mt-sm-0">
                                                            <img src="{{asset('assets/img/payments/master.png')}}" height="24" alt="master-card-img">
                                                            <img src="{{asset('assets/img/payments/discover.png')}}" height="24" alt="discover-card-img">
                                                            <img src="{{asset('assets/img/payments/visa.png')}}" height="24" alt="visa-card-img">
                                                            <img src="{{asset('assets/img/payments/american express.png')}}" height="24" alt="american-express-card-img">
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="form-group col-md-12">
                                                            <br>
                                                            <label for="amount" class="form-control-label">{{ __('Amount') }}</label>
                                                            <div class="input-group">
                                                                <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="form-group mt-3">
                                                        <input type="submit" value="{{__('Make Payment')}}" class="btn-create badge-blue">
                                                    </div>
                                                </form>

                                            </div>
                                        @endif
                                    </div>
                                </section>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endauth

@endsection
