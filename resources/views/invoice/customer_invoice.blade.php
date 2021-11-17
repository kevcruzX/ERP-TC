@php
    $logo=asset(Storage::url('uploads/logo/'));
    $company_favicon=Utility::companyData($invoice->created_by,'company_favicon');
@endphp
<!DOCTYPE html>

<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <title>{{(Utility::companyData($invoice->created_by,'title_text')) ? Utility::companyData($invoice->created_by,'title_text') : config('app.name', 'AccountGo')}} - {{__('Invoice')}}</title>
  <link rel="icon" href="{{$logo.'/'.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png')}}" type="image" sizes="16x16">

  <link rel="stylesheet" href="{{ asset('assets/libs/@fortawesome/fontawesome-free/css/all.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/libs/animate.css/animate.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-timepicker/css/bootstrap-timepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/libs/bootstrap-daterangepicker/daterangepicker.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">

  @stack('css-page')

  <link rel="stylesheet" href="{{ asset('assets/css/site.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/ac.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/datatables.min.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/stylesheet.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        #card-element {
            border: 1px solid #a3afbb !important;
            border-radius: 10px !important;
            padding: 10px !important;
        }
    </style>
</head>

<body>
<header class="header header-transparent" id="header-main">

</header>

<div class="main-content container">



  <div class="row justify-content-between align-items-center mb-3">
      <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">

          <div class="all-button-box mx-2">
              <a href="{{ route('invoice.pdf', Crypt::encrypt($invoice->id))}}" target="_blank" class="btn btn-xs btn-white btn-icon-only width-auto">
                  {{__('Download')}}
              </a>
          </div>

          @if($invoice->status!=0 && $invoice->getDue() > 0 && (!empty($company_payment_setting) && ($company_payment_setting['is_stripe_enabled'] == 'on' || $company_payment_setting['is_paypal_enabled'] == 'on' || $company_payment_setting['is_paystack_enabled'] == 'on' || $company_payment_setting['is_flutterwave_enabled'] == 'on' || $company_payment_setting['is_razorpay_enabled'] == 'on' || $company_payment_setting['is_mercado_enabled'] == 'on' || $company_payment_setting['is_paytm_enabled'] == 'on' ||
        $company_payment_setting['is_mollie_enabled']  == 'on' || $company_payment_setting['is_paypal_enabled'] == 'on' || $company_payment_setting['is_skrill_enabled'] == 'on' || $company_payment_setting['is_coingate_enabled'] == 'on')))
          <div class="all-button-box">
              <a href="#" class="btn btn-xs btn-white btn-icon-only width-auto" data-toggle="modal" data-target="#paymentModal">
                  {{__('Pay Now')}}
              </a>
          </div>
          @endif
      </div>
  </div>

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
                              <h3 class="invoice-number"></h3>
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
                          @if(\Utility::companyData($invoice->created_by,'shipping_display')=='on')
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
                              <!-- <td>
                                <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$payment->id}}').submit();">
                                  <i class="fas fa-trash"></i>
                                </a>
                                {!! Form::open(['method' => 'post', 'route' => ['invoice.payment.destroy',$invoice->id,$payment->id],'id'=>'delete-form-'.$payment->id]) !!}
                                {!! Form::close() !!}
                              </td> -->
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
</div>

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
                              @if(!empty($company_payment_setting) && ($company_payment_setting['is_stripe_enabled'] == 'on' || $company_payment_setting['is_paypal_enabled'] == 'on' || $company_payment_setting['is_paystack_enabled'] == 'on' || $company_payment_setting['is_flutterwave_enabled'] == 'on' || $company_payment_setting['is_razorpay_enabled'] == 'on' || $company_payment_setting['is_mercado_enabled'] == 'on' || $company_payment_setting['is_paytm_enabled'] == 'on' ||
                              $company_payment_setting['is_mollie_enabled'] ==
                              'on' ||
                              $company_payment_setting['is_paypal_enabled'] == 'on' || $company_payment_setting['is_skrill_enabled'] == 'on' || $company_payment_setting['is_coingate_enabled'] == 'on'))
                                  <ul class="nav nav-pills  mb-3" role="tablist">
                                      @if($company_payment_setting['is_stripe_enabled'] == 'on' && !empty($company_payment_setting['stripe_key']) && !empty($company_payment_setting['stripe_secret']))
                                          <li class="nav-item mb-2">
                                              <a class="btn btn-outline-primary btn-sm active" data-toggle="tab" href="#stripe-payment" role="tab" aria-controls="stripe" aria-selected="true">{{ __('Stripe') }}</a>
                                          </li>
                                      @endif

                                      @if($company_payment_setting['is_paypal_enabled'] == 'on' && !empty($company_payment_setting['paypal_client_id']) && !empty($company_payment_setting['paypal_secret_key']))
                                          <li class="nav-item mb-2">
                                              <a class="btn btn-outline-primary btn-sm ml-1" data-toggle="tab" href="#paypal-payment" role="tab" aria-controls="paypal" aria-selected="false">{{ __('Paypal') }}</a>
                                          </li>
                                      @endif

                                      @if($company_payment_setting['is_paystack_enabled'] == 'on' && !empty($company_payment_setting['paystack_public_key']) && !empty($company_payment_setting['paystack_secret_key']))
                                          <li class="nav-item mb-2">
                                              <a class="btn btn-outline-primary btn-sm ml-1" data-toggle="tab" href="#paystack-payment" role="tab" aria-controls="paystack" aria-selected="false">{{ __('Paystack') }}</a>
                                          </li>
                                      @endif

                                      @if(isset($company_payment_setting['is_flutterwave_enabled']) && $company_payment_setting['is_flutterwave_enabled'] == 'on')
                                          <li class="nav-item mb-2">
                                              <a class="btn btn-outline-primary btn-sm ml-1" data-toggle="tab" href="#flutterwave-payment" role="tab" aria-controls="flutterwave" aria-selected="false">{{ __('Flutterwave') }}</a>
                                          </li>
                                      @endif

                                      @if(isset($company_payment_setting['is_razorpay_enabled']) && $company_payment_setting['is_razorpay_enabled'] == 'on')
                                          <li class="nav-item mb-2">
                                              <a class="btn btn-outline-primary btn-sm ml-1" data-toggle="tab" href="#razorpay-payment" role="tab" aria-controls="razorpay" aria-selected="false">{{ __('Razorpay') }}</a>
                                          </li>
                                      @endif


                                      @if(isset($company_payment_setting['is_mercado_enabled']) && $company_payment_setting['is_mercado_enabled'] == 'on')
                                          <li class="nav-item mb-2">
                                              <a class="btn btn-outline-primary btn-sm ml-1" data-toggle="tab" href="#mercado-payment" role="tab" aria-controls="mercado" aria-selected="false">{{ __('Mercado') }}</a>
                                          </li>
                                      @endif

                                      @if(isset($company_payment_setting['is_paytm_enabled']) && $company_payment_setting['is_paytm_enabled'] == 'on')
                                          <li class="nav-item mb-2">
                                              <a class="btn btn-outline-primary btn-sm ml-1" data-toggle="tab" href="#paytm-payment" role="tab" aria-controls="paytm" aria-selected="false">{{ __('Paytm') }}</a>
                                          </li>
                                      @endif

                                      @if(isset($company_payment_setting['is_mollie_enabled']) && $company_payment_setting['is_mollie_enabled'] == 'on')
                                          <li class="nav-item mb-2">
                                              <a class="btn btn-outline-primary btn-sm ml-1" data-toggle="tab" href="#mollie-payment" role="tab" aria-controls="mollie" aria-selected="false">{{ __('Mollie') }}</a>
                                          </li>
                                      @endif

                                      @if(isset($company_payment_setting['is_skrill_enabled']) && $company_payment_setting['is_skrill_enabled'] == 'on')
                                          <li class="nav-item mb-2">
                                              <a class="btn btn-outline-primary btn-sm ml-1" data-toggle="tab" href="#skrill-payment" role="tab" aria-controls="skrill" aria-selected="false">{{ __('Skrill') }}</a>
                                          </li>
                                      @endif

                                      @if(isset($company_payment_setting['is_coingate_enabled']) && $company_payment_setting['is_coingate_enabled'] == 'on')
                                          <li class="nav-item mb-2">
                                              <a class="btn btn-outline-primary btn-sm ml-1" data-toggle="tab" href="#coingate-payment" role="tab" aria-controls="coingate" aria-selected="false">{{ __('Coingate') }}</a>
                                          </li>
                                      @endif

                                  </ul>
                              @endif

                              <div class="tab-content">
                                  @if(!empty($company_payment_setting) && ($company_payment_setting['is_stripe_enabled'] == 'on' && !empty($company_payment_setting['stripe_key']) && !empty($company_payment_setting['stripe_secret'])))
                                      <div class="tab-pane fade active show" id="stripe-payment" role="tabpanel" aria-labelledby="stripe-payment">
                                          <form method="post" action="{{ route('customer.payment',$invoice->id) }}" class="require-validation" id="payment-form">
                                              @csrf
                                              <div class="row">
                                                  <div class="col-sm-8">
                                                      <div class="custom-radio">
                                                          <label class="font-16 font-weight-bold">{{__('Credit / Debit Card')}}</label>
                                                      </div>
                                                      <p class="mb-0 pt-1 text-sm">{{__('Safe money transfer using your bank account. We support Mastercard, Visa, Discover and American express.')}}</p>
                                                  </div>

                                              </div>
                                              <div class="row">
                                                  <div class="col-md-12">
                                                      <div class="form-group">
                                                          <label for="card-name-on">{{__('Name on card')}}</label>
                                                          <input type="text" name="name" id="card-name-on" class="form-control required" >
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
                                                      <label for="amount">{{ __('Amount') }}</label>
                                                      <div class="input-group">
                                                          <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
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
                                                  <button class="btn btn-sm btn-primary rounded-pill" type="submit">{{ __('Make Payment') }}</button>
                                              </div>
                                          </form>
                                      </div>
                                  @endif

                                  @if(!empty($company_payment_setting) &&  ($company_payment_setting['is_paypal_enabled'] == 'on' && !empty($company_payment_setting['paypal_client_id']) && !empty($company_payment_setting['paypal_secret_key'])))
                                      <div class="tab-pane fade " id="paypal-payment" role="tabpanel" aria-labelledby="paypal-payment">
                                          <form class="w3-container w3-display-middle w3-card-4 " method="POST" id="payment-form" action="{{ route('customer.pay.with.paypal',$invoice->id) }}">
                                              @csrf
                                              <div class="row">
                                                  <div class="form-group col-md-12">
                                                      <label for="amount">{{ __('Amount') }}</label>
                                                      <div class="input-group">
                                                          <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                          <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">
                                                          @error('amount')
                                                          <span class="invalid-amount" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
                                                          @enderror
                                                      </div>
                                                  </div>
                                              </div>
                                              <div class="form-group mt-3">
                                                  <button class="btn btn-sm btn-primary rounded-pill" name="submit" type="submit">{{ __('Make Payment') }}</button>
                                              </div>
                                          </form>
                                      </div>
                                  @endif

                                  @if(isset($company_payment_setting['is_paystack_enabled']) && $company_payment_setting['is_paystack_enabled'] == 'on' && !empty($company_payment_setting['paystack_public_key']) && !empty($company_payment_setting['paystack_secret_key']))
                                      <div class="tab-pane fade " id="paystack-payment" role="tabpanel" aria-labelledby="paypal-payment">
                                          <form class="w3-container w3-display-middle w3-card-4" method="POST" id="paystack-payment-form" action="{{ route('customer.pay.with.paystack') }}">
                                              @csrf
                                              <input type="hidden" name="invoice_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                              <div class="form-group col-md-12">
                                                  <label for="amount">{{ __('Amount') }}</label>
                                                  <div class="input-group">
                                                      <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                      <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">

                                                  </div>
                                              </div>
                                              <div class="form-group mt-3">
                                                  <button class="btn btn-sm btn-primary rounded-pill" name="submit"  id="pay_with_paystack" type="button">{{ __('Make Payment') }}</button>

                                              </div>

                                          </form>
                                      </div>
                                  @endif

                                  @if(isset($company_payment_setting['is_flutterwave_enabled']) && $company_payment_setting['is_flutterwave_enabled'] == 'on' && !empty($company_payment_setting['paystack_public_key']) && !empty($company_payment_setting['paystack_secret_key']))
                                      <div class="tab-pane fade " id="flutterwave-payment" role="tabpanel" aria-labelledby="paypal-payment">
                                          <form role="form" action="{{ route('customer.pay.with.flaterwave') }}" method="post" class="require-validation" id="flaterwave-payment-form">
                                              @csrf
                                              <input type="hidden" name="invoice_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                              <div class="form-group col-md-12">
                                                  <label for="amount">{{ __('Amount') }}</label>
                                                  <div class="input-group">
                                                      <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                      <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">

                                                  </div>
                                              </div>
                                              <div class="form-group mt-3">
                                                  <button class="btn btn-sm btn-primary rounded-pill" name="submit"  id="pay_with_flaterwave" type="button">{{ __('Make Payment') }}</button>

                                              </div>

                                          </form>
                                      </div>
                                  @endif

                                  @if(isset($company_payment_setting['is_razorpay_enabled']) && $company_payment_setting['is_razorpay_enabled'] == 'on')
                                      <div class="tab-pane fade " id="razorpay-payment" role="tabpanel" aria-labelledby="paypal-payment">
                                          <form role="form" action="{{ route('customer.pay.with.razorpay') }}" method="post" class="require-validation" id="razorpay-payment-form">
                                              @csrf
                                              <input type="hidden" name="invoice_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                              <div class="form-group col-md-12">
                                                  <label for="amount">{{ __('Amount') }}</label>
                                                  <div class="input-group">
                                                      <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                      <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">

                                                  </div>
                                              </div>
                                              <div class="form-group mt-3">
                                                  <button class="btn btn-sm btn-primary rounded-pill" name="submit"  id="pay_with_razorpay" type="button">{{ __('Make Payment') }}</button>
                                              </div>

                                          </form>
                                      </div>
                                  @endif

                                  @if(isset($company_payment_setting['is_mercado_enabled']) && $company_payment_setting['is_mercado_enabled'] == 'on')



                                      <div class="tab-pane fade " id="mercado-payment" role="tabpanel" aria-labelledby="mercado-payment">
                                          <form role="form" action="{{ route('customer.pay.with.mercado') }}" method="post" class="require-validation" id="mercado-payment-form">
                                              @csrf
                                              <input type="hidden" name="invoice_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                              <div class="form-group col-md-12">
                                                  <label for="amount">{{ __('Amount') }}</label>
                                                  <div class="input-group">
                                                      <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                      <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">

                                                  </div>
                                              </div>
                                              <div class="form-group mt-3">
                                                  <button class="btn btn-sm btn-primary rounded-pill" name="submit"  id="pay_with_mercado" type="submit">{{ __('Make Payment') }}</button>
                                              </div>

                                          </form>
                                      </div>
                                  @endif

                                  @if(isset($company_payment_setting['is_paytm_enabled']) && $company_payment_setting['is_paytm_enabled'] == 'on')
                                      <div class="tab-pane fade" id="paytm-payment" role="tabpanel" aria-labelledby="paytm-payment">
                                          <form role="form" action="{{ route('customer.pay.with.paytm') }}" method="post" class="require-validation" id="paytm-payment-form">
                                              @csrf
                                              <input type="hidden" name="invoice_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                              <div class="form-group col-md-12">
                                                  <label for="amount">{{ __('Amount') }}</label>
                                                  <div class="input-group">
                                                      <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                      <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">

                                                  </div>
                                              </div>
                                              <div class="col-md-12">
                                                  <div class="form-group">
                                                      <label for="flaterwave_coupon" class=" text-dark">{{__('Mobile Number')}}</label>
                                                      <input type="text" id="mobile" name="mobile" class="form-control mobile" data-from="mobile" placeholder="{{ __('Enter Mobile Number') }}" required>
                                                  </div>
                                              </div>
                                              <div class="form-group mt-3">
                                                  <button class="btn btn-sm btn-primary rounded-pill" name="submit"  id="pay_with_paytm" type="submit">{{ __('Make Payment') }}</button>
                                              </div>

                                          </form>
                                      </div>
                                  @endif

                                  @if(isset($company_payment_setting['is_mollie_enabled']) && $company_payment_setting['is_mollie_enabled'] == 'on')
                                      <div class="tab-pane fade " id="mollie-payment" role="tabpanel" aria-labelledby="mollie-payment">
                                          <form role="form" action="{{ route('customer.pay.with.mollie') }}" method="post" class="require-validation" id="mollie-payment-form">
                                              @csrf
                                              <input type="hidden" name="invoice_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                              <div class="form-group col-md-12">
                                                  <label for="amount">{{ __('Amount') }}</label>
                                                  <div class="input-group">
                                                      <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                      <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">

                                                  </div>
                                              </div>
                                              <div class="form-group mt-3">
                                                  <button class="btn btn-sm btn-primary rounded-pill" name="submit"  id="pay_with_mollie" type="submit">{{ __('Make Payment') }}</button>
                                              </div>

                                          </form>
                                      </div>
                                  @endif

                                  @if(isset($company_payment_setting['is_skrill_enabled']) && $company_payment_setting['is_skrill_enabled'] == 'on')
                                      <div class="tab-pane fade " id="skrill-payment" role="tabpanel" aria-labelledby="skrill-payment">
                                          <form role="form" action="{{ route('customer.pay.with.skrill') }}" method="post" class="require-validation" id="skrill-payment-form">
                                              @csrf
                                              <input type="hidden" name="invoice_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                              <div class="form-group col-md-12">
                                                  <label for="amount">{{ __('Amount') }}</label>
                                                  <div class="input-group">
                                                      <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                      <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">

                                                  </div>
                                              </div>
                                              @php
                                                  $skrill_data = [
                                                      'transaction_id' => md5(date('Y-m-d') . strtotime('Y-m-d H:i:s') . 'user_id'),
                                                      'user_id' => 'user_id',
                                                      'amount' => 'amount',
                                                      'currency' => 'currency',
                                                  ];
                                                  session()->put('skrill_data', $skrill_data);

                                              @endphp
                                              <div class="form-group mt-3">
                                                  <button class="btn btn-sm btn-primary rounded-pill" name="submit"  id="pay_with_skrill" type="submit">{{ __('Make Payment') }}</button>
                                              </div>

                                          </form>
                                      </div>
                                  @endif

                                  @if(isset($company_payment_setting['is_coingate_enabled']) && $company_payment_setting['is_coingate_enabled'] == 'on')
                                      <div class="tab-pane fade " id="coingate-payment" role="tabpanel" aria-labelledby="coingate-payment">
                                          <form role="form" action="{{ route('customer.pay.with.coingate') }}" method="post" class="require-validation" id="coingate-payment-form">
                                              @csrf
                                              <input type="hidden" name="invoice_id" value="{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}">

                                              <div class="form-group col-md-12">
                                                  <label for="amount">{{ __('Amount') }}</label>
                                                  <div class="input-group">
                                                      <span class="input-group-prepend"><span class="input-group-text">{{ Utility::getValByName('site_currency') }}</span></span>
                                                      <input class="form-control" required="required" min="0" name="amount" type="number" value="{{$invoice->getDue()}}" min="0" step="0.01" max="{{$invoice->getDue()}}" id="amount">

                                                  </div>
                                              </div>
                                              <div class="form-group mt-3">
                                                  <button class="btn btn-sm btn-primary rounded-pill" name="submit"  id="pay_with_coingate" type="submit">{{ __('Make Payment') }}</button>

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
</div>

<footer id="footer-main">
    <div class="footer-dark">
        <div class="container">
            <div class="row align-items-center justify-content-md-between py-4 mt-4 delimiter-top">
                <div class="col-md-6">
                    <div class="copyright text-sm font-weight-bold text-center text-md-left">
                        {{!empty($companySettings['footer_text']) ? $companySettings['footer_text']->value : ''}}
                    </div>
                </div>
                <div class="col-md-6">
                    <ul class="nav justify-content-center justify-content-md-end mt-3 mt-md-0">
                        <li class="nav-item">
                            <a class="nav-link" href="#" target="_blank">
                                <i class="fab fa-dribbble"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" target="_blank">
                                <i class="fab fa-instagram"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" target="_blank">
                                <i class="fab fa-github"></i>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" target="_blank">
                                <i class="fab fa-facebook"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>
<script src="{{ asset('assets/js/site.core.js') }}"></script>

<script src="{{ asset('assets/libs/progressbar.js/dist/progressbar.min.js') }}"></script>
<script src="{{ asset('assets/libs/moment/min/moment.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-timepicker/js/bootstrap-timepicker.js') }}"></script>
<script src="{{ asset('assets/libs/bootstrap-daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ asset('assets/libs/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset('assets/libs/nicescroll/jquery.nicescroll.min.js')}} "></script>
<script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js')}}"></script>
<script>moment.locale('en');</script>
<script src="{{ asset('assets/libs/autosize/dist/autosize.min.js') }}"></script>
<script src="{{ asset('assets/js/site.js') }}"></script>
<script src="{{ asset('assets/js/demo.js') }} "></script>
<script src="{{ asset('assets/js/datatables.min.js') }}"></script>
<script src="{{ asset('assets/js/jscolor.js') }} "></script>
<script >
    var toster_pos='right';
</script>
<script src="{{ asset('assets/js/custom.js') }} "></script>
<script src="https://js.stripe.com/v3/"></script>
<script src="https://js.paystack.co/v1/inline.js"></script>
<script src="https://api.ravepay.co/flwv3-pug/getpaidx/api/flwpbf-inline.js"></script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script src="{{ asset('js/jquery.form.js') }}"></script>
<script type="text/javascript">
  @if($invoice->status != 0 && $invoice->getDue() > 0 &&  $company_payment_setting['is_stripe_enabled'] == 'on' && !empty($company_payment_setting['stripe_key']) && !empty($company_payment_setting['stripe_secret']))
    var stripe = Stripe('{{$company_payment_setting['stripe_key']}}');
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

    @if(isset($company_payment_setting['paystack_public_key']))
    $(document).on("click", "#pay_with_paystack", function () {
        $('#paystack-payment-form').ajaxForm(function (res) {
            var amount = res.total_price;
            if (res.flag == 1) {
                var paystack_callback = "{{ url('/customer/paystack') }}";

                var handler = PaystackPop.setup({
                    key: '{{ $company_payment_setting['paystack_public_key']  }}',
                    email: res.email,
                    amount: res.total_price * 100,
                    currency: res.currency,
                    ref: 'pay_ref_id' + Math.floor((Math.random() * 1000000000) +
                        1
                    ), // generates a pseudo-unique reference. Please replace with a reference you generated. Or remove the line entirely so our API will generate one for you
                    metadata: {
                        custom_fields: [{
                            display_name: "Email",
                            variable_name: "email",
                            value: res.email,
                        }]
                    },

                    callback: function (response) {

                        window.location.href = paystack_callback + '/' + response.reference + '/' + '{{encrypt($invoice->id)}}' + '?amount=' + amount;
                    },
                    onClose: function () {
                        alert('window closed');
                    }
                });
                handler.openIframe();
            } else if (res.flag == 2) {
                toastrs('Error', res.msg, 'msg');
            } else {
                toastrs('Error', res.message, 'msg');
            }

        }).submit();
    });
  @endif

  @if(isset($company_payment_setting['flutterwave_public_key']))
  //    Flaterwave Payment
  $(document).on("click", "#pay_with_flaterwave", function () {
      $('#flaterwave-payment-form').ajaxForm(function (res) {

          if (res.flag == 1) {
              var amount = res.total_price;
              var API_publicKey = '{{ $company_payment_setting['flutterwave_public_key']  }}';
              var nowTim = "{{ date('d-m-Y-h-i-a') }}";
              var flutter_callback = "{{ url('/customer/flaterwave') }}";
              var x = getpaidSetup({
                  PBFPubKey: API_publicKey,
                  customer_email: '{{$user->email}}',
                  amount: res.total_price,
                  currency: '{{Utility::getValByName('site_currency')}}',
                  txref: nowTim + '__' + Math.floor((Math.random() * 1000000000)) + 'fluttpay_online-' + '{{ date('Y-m-d') }}',
                  meta: [{
                      metaname: "payment_id",
                      metavalue: "id"
                  }],
                  onclose: function () {
                  },
                  callback: function (response) {
                      var txref = response.tx.txRef;
                      if (
                          response.tx.chargeResponseCode == "00" ||
                          response.tx.chargeResponseCode == "0"
                      ) {
                          window.location.href = flutter_callback + '/' + txref + '/' + '{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}} ?amount='+amount;
                      } else {
                          // redirect to a failure page.
                      }
                      x.close(); // use this to close the modal immediately after payment.
                  }
              });
          } else if (res.flag == 2) {
              toastrs('Error', res.msg, 'msg');
          } else {
              toastrs('Error', data.message, 'msg');
          }

      }).submit();
  });
  @endif

  @if(isset($company_payment_setting['razorpay_public_key']))
  // Razorpay Payment
  $(document).on("click", "#pay_with_razorpay", function () {
      $('#razorpay-payment-form').ajaxForm(function (res) {
          if (res.flag == 1) {
              var amount = res.total_price;
              var razorPay_callback = '{{url('/customer/razorpay')}}';
              var totalAmount = res.total_price * 100;
              var coupon_id = res.coupon;
              var options = {
                  "key": "{{ $company_payment_setting['razorpay_public_key']  }}", // your Razorpay Key Id
                  "amount": totalAmount,
                  "name": 'Invoice',
                  "currency": '{{Utility::getValByName('site_currency')}}',
                  "description": "",
                  "handler": function (response) {
                      window.location.href = razorPay_callback + '/' + response.razorpay_payment_id + '/' + '{{\Illuminate\Support\Facades\Crypt::encrypt($invoice->id)}}' + '?amount=' + amount;
                  },
                  "theme": {
                      "color": "#528FF0"
                  }
              };
              var rzp1 = new Razorpay(options);
              rzp1.open();
          } else if (res.flag == 2) {
              toastrs('Error', res.msg, 'msg');
          } else {
              toastrs('Error', data.message, 'msg');
          }

      }).submit();
  });
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
@if($message = Session::get('success'))
    <script>
        show_toastr('Success', '{!! $message !!}', 'success');
    </script>
@endif
@if($message = Session::get('error'))
    <script>
        show_toastr('Error', '{!! $message !!}', 'error');
    </script>
@endif
</body>

</html>
