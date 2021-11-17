@extends('layouts.admin')
@php
    $dir = asset(Storage::url('uploads/plan'));
@endphp
@section('page-title')
    {{__('Manage Plan')}}
@endsection

@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
        @can('create plan')


        @if(!empty($admin_payment_setting) && ($admin_payment_setting['is_paypal_enabled'] == 'on' || $admin_payment_setting['is_paystack_enabled'] == 'on' || $admin_payment_setting['is_flutterwave_enabled'] == 'on'|| $admin_payment_setting['is_razorpay_enabled'] == 'on' || $admin_payment_setting['is_mercado_enabled'] == 'on'|| $admin_payment_setting['is_paytm_enabled'] == 'on'  || $admin_payment_setting['is_mollie_enabled'] == 'on'||
            $admin_payment_setting['is_skrill_enabled'] == 'on' || $admin_payment_setting['is_coingate_enabled'] == 'on'))
                <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                    <a href="#" data-url="{{ route('plans.create') }}" data-ajax-popup="true" data-title="{{__('Create New Plan')}}" class="btn btn-xs btn-white btn-icon-only width-auto">
                        <i class="fas fa-plus"></i> {{__('Create')}}
                    </a>
                </div>
            @endif
        @endcan
    </div>
@endsection

@section('content')

    <div class="row">
        @foreach($plans as $plan)
            <div class="col-lg-4 col-xl-3 col-md-6 col-sm-6 mb-4">
                <div class="plan-3">
                    <h6>{{$plan->name}}</h6>
                    <p class="price">
                        <sup>{{(env('CURRENCY_SYMBOL')) ? env('CURRENCY_SYMBOL') : '$'}}</sup>
                        {{$plan->price}}
                        <sub>{{__('Duration : ').ucfirst($plan->duration)}}</sub>
                    </p>
                    <p class="price-text"></p>
                    <div class="row">
                      <div class="col-6">
                        <ul class="plan-detail">
                            <li>{{($plan->max_users==-1)?__('Unlimited'):$plan->max_users}} {{__('Users')}}</li>
                            <li>{{($plan->max_customers==-1)?__('Unlimited'):$plan->max_customers}} {{__('Customers')}}</li>
                            <li>{{($plan->max_venders==-1)?__('Unlimited'):$plan->max_venders}} {{__('Vendors')}}</li>
                            <li>{{($plan->max_clients==-1)?__('Unlimited'):$plan->max_clients}} {{__('Clients')}}</li>
                        </ul>
                      </div>
                      <div class="col-6">
                        <ul class="plan-detail">
                            <li>{{($plan->account==1)?__('Enable'):__('Disable')}} {{__('Account')}}</li>
                            <li>{{($plan->crm==1)?__('Enable'):__('Disable')}} {{__('CRM')}}</li>
                            <li>{{($plan->hrm==1)?__('Enable'):__('Disable')}} {{__('HRM')}}</li>
                            <li>{{($plan->project==1)?__('Enable'):__('Disable')}} {{__('Project')}}</li>
                        </ul>
                      </div>
                    </div>
                    @can('edit plan')
                        <a title="{{__('Edit Plan')}}" href="#" class="button text-xs" data-url="{{ route('plans.edit',$plan->id) }}" data-ajax-popup="true" data-title="{{__('Edit Plan')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                            <i class="far fa-edit"></i>
                        </a>
                    @endcan
                    @if(!empty($admin_payment_setting) && ($admin_payment_setting['is_paypal_enabled'] == 'on' || $admin_payment_setting['is_paystack_enabled'] == 'on' || $admin_payment_setting['is_flutterwave_enabled'] == 'on'|| $admin_payment_setting['is_razorpay_enabled'] == 'on' || $admin_payment_setting['is_mercado_enabled'] == 'on'|| $admin_payment_setting['is_paytm_enabled'] == 'on'  || $admin_payment_setting['is_mollie_enabled'] == 'on'||
             $admin_payment_setting['is_skrill_enabled'] == 'on' || $admin_payment_setting['is_coingate_enabled'] == 'on')))
                        @can('buy plan')
                            @if($plan->id != \Auth::user()->plan)
                                @if($plan->price > 0)
                                    <a href="{{route('stripe',\Illuminate\Support\Facades\Crypt::encrypt($plan->id))}}" class="button text-xs">{{__('Buy Plan')}}</a>
                                @else
                                    <a href="#" class="button text-xs">{{__('Free')}}</a>
                                @endif
                            @endif
                        @endcan
                    @endif
                    @if(\Auth::user()->type=='company' && \Auth::user()->plan == $plan->id)
                        <p class="server-plan text-white">
                            {{__('Plan Expired : ') }} {{!empty($user->plan_expire_date) ? \Auth::user()->dateFormat($user->plan_expire_date):'Unlimited'}}
                        </p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
@endsection
