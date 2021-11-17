@extends('layouts.admin')
@section('page-title')
    {{__('Settings')}}
@endsection
@php
    $logo=asset(Storage::url('uploads/logo/'));
    $company_logo=Utility::getValByName('company_logo');
    $company_favicon=Utility::getValByName('company_favicon');
@endphp
@push('script-page')
    <script>
        $(document).on("change", "select[name='invoice_template'], input[name='invoice_color']", function () {
            var template = $("select[name='invoice_template']").val();
            var color = $("input[name='invoice_color']:checked").val();
            $('#invoice_frame').attr('src', '{{url('/invoices/preview')}}/' + template + '/' + color);
        });

        $(document).on("change", "select[name='proposal_template'], input[name='proposal_color']", function () {
            var template = $("select[name='proposal_template']").val();
            var color = $("input[name='proposal_color']:checked").val();
            $('#proposal_frame').attr('src', '{{url('/proposal/preview')}}/' + template + '/' + color);
        });

        $(document).on("change", "select[name='bill_template'], input[name='bill_color']", function () {
            var template = $("select[name='bill_template']").val();
            var color = $("input[name='bill_color']:checked").val();
            $('#bill_frame').attr('src', '{{url('/bill/preview')}}/' + template + '/' + color);
        });
    </script>
@endpush
@section('content')
    <div class="row">
        <div class="col-lg-12">
            <section class="nav-tabs">
                <div class="col-lg-12 our-system">
                    <div class="row">
                        <ul class="nav nav-tabs my-4">
                            <li>
                                <a data-toggle="tab" href="#business-setting" class="active">{{__('Business Setting')}}</a>
                            </li>
                            <li class="annual-billing">
                                <a data-toggle="tab" href="#system-setting" class="">{{__('System Setting')}} </a>
                            </li>
                            <li class="annual-billing">
                                <a data-toggle="tab" href="#company-setting" class="">{{__('Company Setting')}} </a>
                            </li>

                            <li class="annual-billing">
                                <a data-toggle="tab" href="#payment-setting" class="">{{__('Payment Setting')}} </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="tab-content">
                    <div id="business-setting" class="tab-pane in active">
                        {{Form::model($settings,array('route'=>'business.setting','method'=>'POST','enctype' => "multipart/form-data"))}}
                        <div class="row justify-content-between align-items-center">
                            <div class="col-md-6 col-sm-6 mb-3 mb-md-0">
                                <h4 class="h4 font-weight-400 float-left pb-2">{{__('Business settings')}}</h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-6 col-md-6">
                                <h4 class="small-title">{{__('Logo')}}</h4>
                                <div class="card setting-card setting-logo-box">
                                    <div class="logo-content">
                                        <img src="{{$logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo.png')}}" class="big-logo" alt=""/>
                                    </div>
                                    <div class="choose-file mt-4">
                                        <label for="company_logo">
                                            <div>{{__('Choose file here')}}</div>
                                            <input type="file" class="form-control" name="company_logo" id="company_logo" data-filename="edit-company_logo">
                                        </label>
                                        <p class="edit-company_logo"></p>
                                    </div>
                                    <p class="mt-3 text-primary"> {{__('These Logo will appear on Bill and Invoice as well.')}}</p>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-md-6">
                                <h4 class="small-title">{{__('Favicon')}}</h4>
                                <div class="card setting-card setting-logo-box">
                                    <div class="logo-content">
                                        <img src="{{$logo.'/'.(isset($company_favicon) && !empty($company_favicon)?$company_favicon:'favicon.png')}}" class="small-logo" alt=""/>
                                    </div>
                                    <div class="choose-file mt-5">
                                        <label for="company_favicon">
                                            <div>{{__('Choose file here')}}</div>
                                            <input type="file" class="form-control" name="company_favicon" id="company_favicon" data-filename="edit-company-favicon">
                                        </label>
                                        <p class="edit-company-favicon"></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-6 col-md-6">
                                <h4 class="small-title">{{__('Settings')}}</h4>
                                <div class="card setting-card setting-logo-box">
                                    <div class="form-group">
                                        {{Form::label('title_text',__('Title Text'),array('class'=>'form-control-label'),array('class'=>'form-control-label')) }}
                                        {{Form::text('title_text',null,array('class'=>'form-control','placeholder'=>__('Title Text')))}}
                                        @error('title_text')
                                        <span class="invalid-title_text" role="alert">
                                             <strong class="text-danger">{{ $message }}</strong>
                                             </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mb-4">
                            <div class="col-lg-12 text-right">
                                <input type="submit" value="{{__('Save Changes')}}" class="btn-submit">
                            </div>
                        </div>
                        {{Form::close()}}
                    </div>
                    <div id="system-setting" class="tab-pane">
                        <div class="col-md-12">
                            <div class="row justify-content-between align-items-center">
                                <div class="col-md-6 col-sm-6 mb-3 mb-md-0">
                                    <h4 class="h4 font-weight-400 float-left pb-2">{{__('System Settings')}}</h4>
                                </div>
                            </div>
                            <div class="card bg-none">
                                {{Form::model($settings,array('route'=>'system.settings','method'=>'post'))}}
                                <div class="card-body pd-0">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            {{Form::label('site_currency',__('Currency *'),array('class'=>'form-control-label')) }}
                                            {{Form::text('site_currency',null,array('class'=>'form-control font-style'))}}
                                            <small> {{__('Note: Add currency code as per three-letter ISO code.')}}<br> <a href="https://stripe.com/docs/currencies" target="_blank">{{__('you can find out here..')}}</a></small> <br>
                                            @error('site_currency')
                                            <span class="invalid-site_currency" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('site_currency_symbol',__('Currency Symbol *'),array('class'=>'form-control-label')) }}
                                            {{Form::text('site_currency_symbol',null,array('class'=>'form-control'))}}
                                            @error('site_currency_symbol')
                                            <span class="invalid-site_currency_symbol" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label class="form-control-label" for="example3cols3Input">{{__('Currency Symbol Position')}}</label>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-radio mb-3">

                                                            <input type="radio" id="customRadio5" name="site_currency_symbol_position" value="pre" class="custom-control-input" @if(@$settings['site_currency_symbol_position'] == 'pre') checked @endif>
                                                            <label class="custom-control-label" for="customRadio5">{{__('Pre')}}</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-radio mb-3">
                                                            <input type="radio" id="customRadio6" name="site_currency_symbol_position" value="post" class="custom-control-input" @if(@$settings['site_currency_symbol_position'] == 'post') checked @endif>
                                                            <label class="custom-control-label" for="customRadio6">{{__('Post')}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="site_date_format" class="form-control-label">{{__('Date Format')}}</label>
                                            <select type="text" name="site_date_format" class="form-control select2" id="site_date_format">
                                                <option value="M j, Y" @if(@$settings['site_date_format'] == 'M j, Y') selected="selected" @endif>Jan 1,2015</option>
                                                <option value="d-m-Y" @if(@$settings['site_date_format'] == 'd-m-Y') selected="selected" @endif>d-m-y</option>
                                                <option value="m-d-Y" @if(@$settings['site_date_format'] == 'm-d-Y') selected="selected" @endif>m-d-y</option>
                                                <option value="Y-m-d" @if(@$settings['site_date_format'] == 'Y-m-d') selected="selected" @endif>y-m-d</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="site_time_format" class="form-control-label">{{__('Time Format')}}</label>
                                            <select type="text" name="site_time_format" class="form-control select2" id="site_time_format">
                                                <option value="g:i A" @if(@$settings['site_time_format'] == 'g:i A') selected="selected" @endif>10:30 PM</option>
                                                <option value="g:i a" @if(@$settings['site_time_format'] == 'g:i a') selected="selected" @endif>10:30 pm</option>
                                                <option value="H:i" @if(@$settings['site_time_format'] == 'H:i') selected="selected" @endif>22:30</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('invoice_prefix',__('Invoice Prefix'),array('class'=>'form-control-label')) }}
                                            {{Form::text('invoice_prefix',null,array('class'=>'form-control'))}}
                                            @error('invoice_prefix')
                                            <span class="invalid-invoice_prefix" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('proposal_prefix',__('Proposal Prefix'),array('class'=>'form-control-label')) }}
                                            {{Form::text('proposal_prefix',null,array('class'=>'form-control'))}}
                                            @error('proposal_prefix')
                                            <span class="invalid-proposal_prefix" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('bill_prefix',__('Bill Prefix'),array('class'=>'form-control-label')) }}
                                            {{Form::text('bill_prefix',null,array('class'=>'form-control'))}}
                                            @error('bill_prefix')
                                            <span class="invalid-bill_prefix" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('customer_prefix',__('Customer Prefix'),array('class'=>'form-control-label')) }}
                                            {{Form::text('customer_prefix',null,array('class'=>'form-control'))}}
                                            @error('customer_prefix')
                                            <span class="invalid-customer_prefix" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('vender_prefix',__('Vender Prefix'),array('class'=>'form-control-label')) }}
                                            {{Form::text('vender_prefix',null,array('class'=>'form-control'))}}
                                            @error('vender_prefix')
                                            <span class="invalid-vender_prefix" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('footer_title',__('Invoice/Bill Footer Title'),array('class'=>'form-control-label')) }}
                                            {{Form::text('footer_title',null,array('class'=>'form-control'))}}
                                            @error('footer_title')
                                            <span class="invalid-footer_title" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            {{Form::label('decimal_number',__('Decimal Number Format'),array('class'=>'form-control-label')) }}
                                            {{Form::number('decimal_number', null, ['class'=>'form-control'])}}
                                            @error('decimal_number')
                                            <span class="invalid-decimal_number" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('journal_prefix',__('Journal Prefix'),array('class'=>'form-control-label')) }}
                                            {{Form::text('journal_prefix',null,array('class'=>'form-control'))}}
                                            @error('journal_prefix')
                                            <span class="invalid-journal_prefix" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>

                                        <div class="form-group col-md-6">
                                            {{Form::label('shipping_display',__('Shipping Display in Proposal / Invoice / Bill ?'),array('class'=>'form-control-label')) }}
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="email-template-checkbox custom-control-input" name="shipping_display" id="email_tempalte_13" {{($settings['shipping_display']=='on')?'checked':''}} >
                                                <label class="custom-control-label form-control-label" for="email_tempalte_13"></label>
                                            </div>

                                            @error('shipping_display')
                                            <span class="invalid-shipping_display" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('footer_notes',__('Invoice/Bill Footer Notes'),array('class'=>'form-control-label')) }}
                                            {{Form::textarea('footer_notes', null, ['class'=>'form-control','rows'=>'3'])}}
                                            @error('footer_notes')
                                            <span class="invalid-footer_notes" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-12  text-right">
                                    <input type="submit" value="{{__('Save Changes')}}" class="btn-submit text-white">
                                </div>
                                {{Form::close()}}
                            </div>
                        </div>
                    </div>
                    <div id="company-setting" class="tab-pane">
                        <div class="col-md-12">
                            <div class="row justify-content-between align-items-center">
                                <div class="col-md-6 col-sm-6 mb-3 mb-md-0">
                                    <h4 class="h4 font-weight-400 float-left pb-2">{{__('Company Settings')}}</h4>
                                </div>
                            </div>
                            <div class="card bg-none">
                                {{Form::model($settings,array('route'=>'company.settings','method'=>'post'))}}
                                <div class="card-body pd-0">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            {{Form::label('company_name *',__('Company Name *'),array('class'=>'form-control-label')) }}
                                            {{Form::text('company_name',null,array('class'=>'form-control font-style'))}}
                                            @error('company_name')
                                            <span class="invalid-company_name" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('company_address',__('Address'),array('class'=>'form-control-label')) }}
                                            {{Form::text('company_address',null,array('class'=>'form-control font-style'))}}
                                            @error('company_address')
                                            <span class="invalid-company_address" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('company_city',__('City'),array('class'=>'form-control-label')) }}
                                            {{Form::text('company_city',null,array('class'=>'form-control font-style'))}}
                                            @error('company_city')
                                            <span class="invalid-company_city" role="alert">
                                                                    <strong class="text-danger">{{ $message }}</strong>
                                                                </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('company_state',__('State'),array('class'=>'form-control-label')) }}
                                            {{Form::text('company_state',null,array('class'=>'form-control font-style'))}}
                                            @error('company_state')
                                            <span class="invalid-company_state" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('company_zipcode',__('Zip/Post Code'),array('class'=>'form-control-label')) }}
                                            {{Form::text('company_zipcode',null,array('class'=>'form-control'))}}
                                            @error('company_zipcode')
                                            <span class="invalid-company_zipcode" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group  col-md-6">
                                            {{Form::label('company_country',__('Country'),array('class'=>'form-control-label')) }}
                                            {{Form::text('company_country',null,array('class'=>'form-control font-style'))}}
                                            @error('company_country')
                                            <span class="invalid-company_country" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('company_telephone',__('Telephone'),array('class'=>'form-control-label')) }}
                                            {{Form::text('company_telephone',null,array('class'=>'form-control'))}}
                                            @error('company_telephone')
                                            <span class="invalid-company_telephone" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('company_email',__('System Email *'),array('class'=>'form-control-label')) }}
                                            {{Form::text('company_email',null,array('class'=>'form-control'))}}
                                            @error('company_email')
                                            <span class="invalid-company_email" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('company_email_from_name',__('Email (From Name) *'),array('class'=>'form-control-label')) }}
                                            {{Form::text('company_email_from_name',null,array('class'=>'form-control font-style'))}}
                                            @error('company_email_from_name')
                                            <span class="invalid-company_email_from_name" role="alert">
                                                            <strong class="text-danger">{{ $message }}</strong>
                                                        </span>
                                            @enderror
                                        </div>
                                        <div class="form-group col-md-6">
                                            {{Form::label('registration_number',__('Company Registration Number *'),array('class'=>'form-control-label')) }}
                                            {{Form::text('registration_number',null,array('class'=>'form-control'))}}

                                        </div>

                                        <div class="form-group col-md-6">
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-radio mb-3">
                                                            <input type="radio" id="customRadio8" name="tax_type" value="VAT" class="custom-control-input" {{($settings['tax_type'] == 'VAT')?'checked':''}} >
                                                            <label class="custom-control-label" for="customRadio8">{{__('VAT Number')}}</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="custom-control custom-radio mb-3">
                                                            <input type="radio" id="customRadio7" name="tax_type" value="GST" class="custom-control-input" {{($settings['tax_type'] == 'GST')?'checked':''}}>
                                                            <label class="custom-control-label" for="customRadio7">{{__('GST Number')}}</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{Form::text('vat_number',null,array('class'=>'form-control','placeholder'=>__('Enter VAT / GST Number')))}}

                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-lg-12  text-right">
                                    <input type="submit" value="{{__('Save Changes')}}" class="btn-submit text-white">
                                </div>
                                {{Form::close()}}
                            </div>
                        </div>
                    </div>
                    <div id="payment-setting" class="tab-pane">
                        <div class="col-md-12">
                            <div class="row justify-content-between align-items-center">
                                <div class="col-md-6 col-sm-6 mb-3 mb-md-0">
                                    <h4 class="h4 font-weight-400 float-left pb-2">{{__('Payment settings')}}</h4>
                                </div>
                            </div>
                            <div class="card bg-none company-setting">
                                {{Form::model($settings,['route'=>'company.payment.settings', 'method'=>'POST'])}}
                                <div id="accordion-2" class="accordion accordion-spaced">
                                    <!-- Strip -->
                                    <div class="card">
                                        <div class="card-header py-4" id="heading-2-2" data-toggle="collapse" role="button" data-target="#collapse-2-2" aria-expanded="false" aria-controls="collapse-2-2">
                                            <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Stripe')}}</h6>
                                        </div>
                                        <div id="collapse-2-2" class="collapse" aria-labelledby="heading-2-2" data-parent="#accordion-2">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6 py-2">

                                                        <small class=""> {{__('Note: This detail will use for invoice payment.')}}</small>
                                                    </div>
                                                    <div class="col-6 py-2 text-right">
                                                        <div class="custom-control custom-switch">
                                                            <input type="hidden" name="is_stripe_enabled" value="off">
                                                            <input type="checkbox" class="custom-control-input" name="is_stripe_enabled" id="is_stripe_enabled" {{ isset($company_payment_setting['is_stripe_enabled']) && $company_payment_setting['is_stripe_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                            <label class="custom-control-label form-control-label" for="is_stripe_enabled">{{__('Enable Stripe')}}</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            {{Form::label('stripe_key',__('Stripe Key'),array('class'=>'form-control-label')) }}
                                                            {{Form::text('stripe_key',isset($company_payment_setting['stripe_key'])?$company_payment_setting['stripe_key']:'',['class'=>'form-control','placeholder'=>__('Enter Stripe Key')])}}
                                                            @if ($errors->has('stripe_key'))
                                                                <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('stripe_key') }}
                                                                    </span>
                                                            @endif

                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            {{Form::label('stripe_secret',__('Stripe Secret'),array('class'=>'form-control-label')) }}
                                                            {{Form::text('stripe_secret',isset($company_payment_setting['stripe_secret'])?$company_payment_setting['stripe_secret']:'',['class'=>'form-control ','placeholder'=>__('Enter Stripe Secret')])}}
                                                            @if ($errors->has('stripe_secret'))
                                                                <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('stripe_secret') }}
                                                                    </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Paypal -->
                                    <div class="card">
                                        <div class="card-header py-4" id="heading-2-3" data-toggle="collapse" role="button" data-target="#collapse-2-3" aria-expanded="false" aria-controls="collapse-2-3">
                                            <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('PayPal')}}</h6>
                                        </div>
                                        <div id="collapse-2-3" class="collapse" aria-labelledby="heading-2-3" data-parent="#accordion-2">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6 py-2">

                                                        <small class=""> {{__('Note: This detail will use for invoice payment.')}}</small>
                                                    </div>
                                                    <div class="col-6 py-2 text-right">
                                                        <div class="custom-control custom-switch">
                                                            <input type="hidden" name="is_paypal_enabled" value="off">
                                                            <input type="checkbox" class="custom-control-input" name="is_paypal_enabled" id="is_paypal_enabled" {{ isset($company_payment_setting['is_paypal_enabled']) && $company_payment_setting['is_paypal_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                            <label class="custom-control-label form-control-label" for="is_paypal_enabled">{{__('Enable Paypal')}}</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-auto pb-4">
                                                        <label class="paypal-label form-control-label" for="paypal_mode">{{__('Paypal Mode')}}</label> <br>
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label class="btn btn-primary btn-sm {{isset($company_payment_setting['paypal_mode']) && $company_payment_setting['paypal_mode'] == 'sandbox' ? 'active' : ''}}">
                                                                <input type="radio" name="paypal_mode" value="sandbox" {{ isset($company_payment_setting['paypal_mode']) && $company_payment_setting['paypal_mode'] == '' || isset($company_payment_setting['paypal_mode']) && $company_payment_setting['paypal_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>{{__('Sandbox')}}
                                                            </label>
                                                            <label class="btn btn-primary btn-sm {{isset($company_payment_setting['paypal_mode']) && $company_payment_setting['paypal_mode'] == 'live' ? 'active' : ''}}">
                                                                <input type="radio" name="paypal_mode" value="live" {{ isset($company_payment_setting['paypal_mode']) && $company_payment_setting['paypal_mode'] == 'live' ? 'checked="checked"' : '' }}>{{__('Live')}}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="paypal_client_id" class="form-control-label">{{ __('Client ID') }}</label>
                                                            <input type="text" name="paypal_client_id" id="paypal_client_id" class="form-control" value="{{isset($company_payment_setting['paypal_client_id'])?$company_payment_setting['paypal_client_id']:''}}" placeholder="{{ __('Client ID') }}"/>
                                                            @if ($errors->has('paypal_client_id'))
                                                                <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('paypal_client_id') }}
                                                                    </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col">
                                                        <div class="form-group">
                                                            <label for="paypal_secret_key" class="form-control-label">{{ __('Secret Key') }}</label>
                                                            <input type="text" name="paypal_secret_key" id="paypal_secret_key" class="form-control" value="{{isset($company_payment_setting['paypal_secret_key'])?$company_payment_setting['paypal_secret_key']:''}}" placeholder="{{ __('Secret Key') }}"/>
                                                            @if ($errors->has('paypal_secret_key'))
                                                                <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('paypal_secret_key') }}
                                                                    </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Paystack -->
                                    <div class="card">
                                        <div class="card-header py-4" id="heading-2-6" data-toggle="collapse" role="button" data-target="#collapse-2-6" aria-expanded="false" aria-controls="collapse-2-6">
                                            <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Paystack')}}</h6>
                                        </div>
                                        <div id="collapse-2-6" class="collapse" aria-labelledby="heading-2-6" data-parent="#accordion-2">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6 py-2">

                                                        <small> {{__('Note: This detail will use for invoice payment.')}}</small>
                                                    </div>
                                                    <div class="col-6 py-2 text-right">
                                                        <div class="custom-control custom-switch">
                                                            <input type="hidden" name="is_paystack_enabled" value="off">
                                                            <input type="checkbox" class="custom-control-input" name="is_paystack_enabled" id="is_paystack_enabled" {{ isset($company_payment_setting['is_paystack_enabled']) && $company_payment_setting['is_paystack_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                            <label class="custom-control-label form-control-label" for="is_paystack_enabled">{{__('Enable Paystack')}}</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paypal_client_id" class="form-control-label">{{ __('Public Key') }}</label>
                                                            <input type="text" name="paystack_public_key" id="paystack_public_key" class="form-control form-control-label" value="{{isset($company_payment_setting['paystack_public_key']) ? $company_payment_setting['paystack_public_key']:''}}" placeholder="{{ __('Public Key') }}"/>
                                                            @if ($errors->has('paystack_public_key'))
                                                                <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('paystack_public_key') }}
                                                                    </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paystack_secret_key" class="form-control-label">{{ __('Secret Key') }}</label>
                                                            <input type="text" name="paystack_secret_key" id="paystack_secret_key" class="form-control form-control-label" value="{{isset($company_payment_setting['paystack_secret_key']) ? $company_payment_setting['paystack_secret_key']:''}}" placeholder="{{ __('Secret Key') }}"/>
                                                            @if ($errors->has('paystack_secret_key'))
                                                                <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('paystack_secret_key') }}
                                                                    </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- FLUTTERWAVE -->
                                    <div class="card">
                                        <div class="card-header py-4" id="heading-2-7" data-toggle="collapse" role="button" data-target="#collapse-2-7" aria-expanded="false" aria-controls="collapse-2-7">
                                            <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Flutterwave')}}</h6>
                                        </div>
                                        <div id="collapse-2-7" class="collapse" aria-labelledby="heading-2-7" data-parent="#accordion-2">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6 py-2">

                                                        <small> {{__('Note: This detail will use for invoice payment.')}}</small>
                                                    </div>
                                                    <div class="col-6 py-2 text-right">
                                                        <div class="custom-control custom-switch">
                                                            <input type="hidden" name="is_flutterwave_enabled" value="off">
                                                            <input type="checkbox" class="custom-control-input" name="is_flutterwave_enabled" id="is_flutterwave_enabled" {{ isset($company_payment_setting['is_flutterwave_enabled'])  && $company_payment_setting['is_flutterwave_enabled']== 'on' ? 'checked="checked"' : '' }}>
                                                            <label class="custom-control-label form-control-label" for="is_flutterwave_enabled">{{__('Enable Flutterwave')}}</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paypal_client_id" class="form-control-label">{{ __('Public Key') }}</label>
                                                            <input type="text" name="flutterwave_public_key" id="flutterwave_public_key" class="form-control" value="{{isset($company_payment_setting['flutterwave_public_key'])?$company_payment_setting['flutterwave_public_key']:''}}" placeholder="{{ __('Public Key') }}"/>
                                                            @if ($errors->has('flutterwave_public_key'))
                                                                <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('flutterwave_public_key') }}
                                                                    </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paystack_secret_key" class="form-control-label">{{ __('Secret Key') }}</label>
                                                            <input type="text" name="flutterwave_secret_key" id="flutterwave_secret_key" class="form-control form-control-label" value="{{isset($company_payment_setting['flutterwave_secret_key'])?$company_payment_setting['flutterwave_secret_key']:''}}" placeholder="{{ __('Secret Key') }}"/>
                                                            @if ($errors->has('flutterwave_secret_key'))
                                                                <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('flutterwave_secret_key') }}
                                                                    </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Razorpay -->
                                    <div class="card">
                                        <div class="card-header py-4" id="heading-2-8" data-toggle="collapse" role="button" data-target="#collapse-2-8" aria-expanded="false" aria-controls="collapse-2-8">
                                            <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Razorpay')}}</h6>
                                        </div>
                                        <div id="collapse-2-8" class="collapse" aria-labelledby="heading-2-7" data-parent="#accordion-2">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6 py-2">

                                                        <small> {{__('Note: This detail will use for invoice payment.')}}</small>
                                                    </div>
                                                    <div class="col-6 py-2 text-right">
                                                        <div class="custom-control custom-switch">
                                                            <input type="hidden" name="is_razorpay_enabled" value="off">
                                                            <input type="checkbox" class="custom-control-input " name="is_razorpay_enabled" id="is_razorpay_enabled" {{ isset($company_payment_setting['is_razorpay_enabled']) && $company_payment_setting['is_razorpay_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                            <label class="custom-control-label form-control-label" for="is_razorpay_enabled">{{__('Enable Razorpay')}}</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paypal_client_id" class="form-control-label">{{ __('Public Key') }}</label>

                                                            <input type="text" name="razorpay_public_key" id="razorpay_public_key" class="form-control" value="{{ isset($company_payment_setting['razorpay_public_key'])?$company_payment_setting['razorpay_public_key']:''}}" placeholder="{{ __('Public Key') }}"/>
                                                            @if ($errors->has('razorpay_public_key'))
                                                                <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('razorpay_public_key') }}
                                                                    </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="paystack_secret_key" class="form-control-label">{{ __('Secret Key') }}</label>
                                                            <input type="text" name="razorpay_secret_key" id="razorpay_secret_key" class="form-control" value="{{ isset($company_payment_setting['razorpay_secret_key'])?$company_payment_setting['razorpay_secret_key']:''}}" placeholder="{{ __('Secret Key') }}"/>
                                                            @if ($errors->has('razorpay_secret_key'))
                                                                <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('razorpay_secret_key') }}
                                                                    </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mercado Pago-->
                                    <div class="card">
                                        <div class="card-header py-4" id="heading-2-12" data-toggle="collapse" role="button" data-target="#collapse-2-12" aria-expanded="false" aria-controls="collapse-2-12">
                                            <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Mercado Pago')}}</h6>
                                        </div>
                                        <div id="collapse-2-12" class="collapse" aria-labelledby="heading-2-12" data-parent="#accordion-2">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6 py-2">
                                                        <h5 class="h5">{{__('Mercado Pago')}}</h5>
                                                        <small> {{__('Note: This detail will use for make checkout of plan.')}}</small>
                                                    </div>
                                                    <div class="col-6 py-2 text-right">
                                                        <div class="custom-control custom-switch">
                                                            <input type="hidden" name="is_mercado_enabled" value="off">
                                                            <input type="checkbox" class="custom-control-input" name="is_mercado_enabled" id="is_mercado_enabled" {{isset($company_payment_setting['is_mercado_enabled']) &&  $company_payment_setting['is_mercado_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                            <label class="custom-control-label form-control-label" for="is_mercado_enabled">{{__('Enable Mercado Pago')}}</label>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="row">
                                                    <div class="col-4 pb-4">
                                                        <label class="coingate-label form-control-label" for="mercado_mode">{{__('Mercado Mode')}}</label> <br>
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label class="btn btn-primary btn-sm {{isset($company_payment_setting['mercado_mode']) && $company_payment_setting['mercado_mode'] == 'sandbox' ? 'active' : ''}}">
                                                                <input type="radio" name="mercado_mode" value="sandbox" {{ isset($company_payment_setting['mercado_mode']) && $company_payment_setting['mercado_mode'] == '' || isset($company_payment_setting['mercado_mode']) && $company_payment_setting['mercado_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>{{__('Sandbox')}}
                                                            </label>
                                                            <label class="btn btn-primary btn-sm {{isset($company_payment_setting['mercado_mode']) && $company_payment_setting['mercado_mode'] == 'live' ? 'active' : ''}}">
                                                                <input type="radio" name="mercado_mode" value="live" {{ isset($company_payment_setting['mercado_mode']) && $company_payment_setting['mercado_mode'] == 'live' ? 'checked="checked"' : '' }}>{{__('Live')}}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-8">
                                                        <div class="form-group">
                                                            <label for="mercado_access_token">{{ __('Access Token') }}</label>
                                                            <input type="text" name="mercado_access_token" id="mercado_access_token" class="form-control" value="{{isset($company_payment_setting['mercado_access_token']) ? $company_payment_setting['mercado_access_token']:''}}" placeholder="{{ __('Access Token') }}"/>
                                                            @if ($errors->has('mercado_secret_key'))
                                                                <span class="invalid-feedback d-block">
                                {{ $errors->first('mercado_access_token') }}
                            </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Paytm -->
                                    <div class="card">
                                        <div class="card-header py-4" id="heading-2-8" data-toggle="collapse" role="button" data-target="#collapse-2-9" aria-expanded="false" aria-controls="collapse-2-9">
                                            <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Paytm')}}</h6>
                                        </div>
                                        <div id="collapse-2-9" class="collapse" aria-labelledby="heading-2-7" data-parent="#accordion-2">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6 py-2">

                                                        <small> {{__('Note: This detail will use for invoice payment.')}}</small>
                                                    </div>
                                                    <div class="col-6 py-2 text-right">
                                                        <div class="custom-control custom-switch">
                                                            <input type="hidden" name="is_paytm_enabled" value="off">
                                                            <input type="checkbox" class="custom-control-input" name="is_paytm_enabled" id="is_paytm_enabled" {{ isset($company_payment_setting['is_paytm_enabled']) && $company_payment_setting['is_paytm_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                            <label class="custom-control-label form-control-label" for="is_paytm_enabled">{{__('Enable Paytm')}}</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-12 pb-4">
                                                        <label class="paypal-label form-control-label" for="paypal_mode">{{__('Paytm Environment')}}</label> <br>
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label class="btn btn-primary btn-sm {{isset($company_payment_setting['paytm_mode']) && $company_payment_setting['paytm_mode'] == 'local' ? 'active' : ''}}">
                                                                <input type="radio" name="paytm_mode" value="local" {{ isset($company_payment_setting['paytm_mode']) && $company_payment_setting['paytm_mode'] == '' || isset($company_payment_setting['paytm_mode']) && $company_payment_setting['paytm_mode'] == 'local' ? 'checked="checked"' : '' }}>{{__('Local')}}
                                                            </label>
                                                            <label class="btn btn-primary btn-sm {{isset($company_payment_setting['paytm_mode']) && $company_payment_setting['paytm_mode'] == 'live' ? 'active' : ''}}">
                                                                <input type="radio" name="paytm_mode" value="production" {{ isset($company_payment_setting['paytm_mode']) && $company_payment_setting['paytm_mode'] == 'production' ? 'checked="checked"' : '' }}>{{__('Production')}}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="paytm_public_key" class="form-control-label">{{ __('Merchant ID') }}</label>
                                                            <input type="text" name="paytm_merchant_id" id="paytm_merchant_id" class="form-control" value="{{isset($company_payment_setting['paytm_merchant_id'])? $company_payment_setting['paytm_merchant_id']:''}}" placeholder="{{ __('Merchant ID') }}"/>
                                                            @if ($errors->has('paytm_merchant_id'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('paytm_merchant_id') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="paytm_secret_key" class="form-control-label">{{ __('Merchant Key') }}</label>
                                                            <input type="text" name="paytm_merchant_key" id="paytm_merchant_key" class="form-control" value="{{ isset($company_payment_setting['paytm_merchant_key']) ? $company_payment_setting['paytm_merchant_key']:''}}" placeholder="{{ __('Merchant Key') }}"/>
                                                            @if ($errors->has('paytm_merchant_key'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('paytm_merchant_key') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="paytm_industry_type" class="form-control-label"> {{ __('Industry Type') }}</label>
                                                            <input type="text" name="paytm_industry_type" id="paytm_industry_type" class="form-control" value="{{isset($company_payment_setting['paytm_industry_type']) ?$company_payment_setting['paytm_industry_type']:''}}" placeholder="{{ __('Industry Type') }}"/>
                                                            @if ($errors->has('paytm_industry_type'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('paytm_industry_type') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Mollie -->
                                    <div class="card">
                                        <div class="card-header py-4" id="heading-2-8" data-toggle="collapse" role="button" data-target="#collapse-2-10" aria-expanded="false" aria-controls="collapse-2-10">
                                            <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Mollie')}}</h6>
                                        </div>
                                        <div id="collapse-2-10" class="collapse" aria-labelledby="heading-2-7" data-parent="#accordion-2">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6 py-2">

                                                        <small> {{__('Note: This detail will use for invoice payment.')}}</small>
                                                    </div>
                                                    <div class="col-6 py-2 text-right">
                                                        <div class="custom-control custom-switch">
                                                            <input type="hidden" name="is_mollie_enabled" value="off">
                                                            <input type="checkbox" class="custom-control-input" name="is_mollie_enabled" id="is_mollie_enabled" {{ isset($company_payment_setting['is_mollie_enabled']) && $company_payment_setting['is_mollie_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                            <label class="custom-control-label form-control-label" for="is_mollie_enabled">{{__('Enable Mollie')}}</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="mollie_api_key" class="form-control-label">{{ __('Mollie Api Key') }}</label>
                                                            <input type="text" name="mollie_api_key" id="mollie_api_key" class="form-control" value="{{ isset($company_payment_setting['mollie_api_key'])?$company_payment_setting['mollie_api_key']:''}}" placeholder="{{ __('Mollie Api Key') }}"/>
                                                            @if ($errors->has('mollie_api_key'))
                                                                <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('mollie_api_key') }}
                                                                    </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="mollie_profile_id" class="form-control-label">{{ __('Mollie Profile Id') }}</label>
                                                            <input type="text" name="mollie_profile_id" id="mollie_profile_id" class="form-control" value="{{ isset($company_payment_setting['mollie_profile_id'])?$company_payment_setting['mollie_profile_id']:''}}" placeholder="{{ __('Mollie Profile Id') }}"/>
                                                            @if ($errors->has('mollie_profile_id'))
                                                                <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('mollie_profile_id') }}
                                                                    </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="mollie_partner_id" class="form-control-label">{{ __('Mollie Partner Id') }}</label>
                                                            <input type="text" name="mollie_partner_id" id="mollie_partner_id" class="form-control" value="{{ isset($company_payment_setting['mollie_partner_id'])?$company_payment_setting['mollie_partner_id']:''}}" placeholder="{{ __('Mollie Partner Id') }}"/>
                                                            @if ($errors->has('mollie_partner_id'))
                                                                <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('mollie_partner_id') }}
                                                                    </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Skrill -->
                                    <div class="card">
                                        <div class="card-header py-4" id="heading-2-8" data-toggle="collapse" role="button" data-target="#collapse-2-13" aria-expanded="false" aria-controls="collapse-2-10">
                                            <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('Skrill')}}</h6>
                                        </div>
                                        <div id="collapse-2-13" class="collapse" aria-labelledby="heading-2-7" data-parent="#accordion-2">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6 py-2">

                                                        <small> {{__('Note: This detail will use for invoice payment.')}}</small>
                                                    </div>
                                                    <div class="col-6 py-2 text-right">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input" name="is_skrill_enabled" id="is_skrill_enabled" {{ isset($company_payment_setting['is_skrill_enabled']) && $company_payment_setting['is_skrill_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                            <label class="custom-control-label form-control-label" for="is_skrill_enabled">{{__('Enable Skrill')}}</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="mollie_api_key" class="form-control-label">{{ __('Skrill Email') }}</label>
                                                            <input type="email" name="skrill_email" id="skrill_email" class="form-control" value="{{ isset($company_payment_setting['skrill_email'])?$company_payment_setting['skrill_email']:''}}" placeholder="{{ __('Mollie Api Key') }}"/>
                                                            @if ($errors->has('skrill_email'))
                                                                <span class="invalid-feedback d-block">
                                                                        {{ $errors->first('skrill_email') }}
                                                                    </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- CoinGate -->
                                    <div class="card">
                                        <div class="card-header py-4" id="heading-2-8" data-toggle="collapse" role="button" data-target="#collapse-2-15" aria-expanded="false" aria-controls="collapse-2-10">
                                            <h6 class="mb-0"><i class="far fa-credit-card mr-3"></i>{{__('CoinGate')}}</h6>
                                        </div>
                                        <div id="collapse-2-15" class="collapse" aria-labelledby="heading-2-7" data-parent="#accordion-2">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6 py-2">

                                                        <small> {{__('Note: This detail will use for invoice payment.')}}</small>
                                                    </div>
                                                    <div class="col-6 py-2 text-right">
                                                        <div class="custom-control custom-switch">
                                                            <input type="checkbox" class="custom-control-input" name="is_coingate_enabled" id="is_coingate_enabled" {{ isset($company_payment_setting['is_coingate_enabled']) && $company_payment_setting['is_coingate_enabled'] == 'on' ? 'checked="checked"' : '' }}>
                                                            <label class="custom-control-label form-control-label" for="is_coingate_enabled">{{__('Enable CoinGate')}}</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 pb-4">
                                                        <label class="coingate-label form-control-label" for="coingate_mode">{{__('CoinGate Mode')}}</label> <br>
                                                        <div class="btn-group btn-group-toggle" data-toggle="buttons">
                                                            <label class="btn btn-primary btn-sm {{isset($company_payment_setting['coingate_mode']) && $company_payment_setting['coingate_mode'] == 'sandbox' ? 'active' : ''}}">
                                                                <input type="radio" name="coingate_mode" value="sandbox" {{ isset($company_payment_setting['coingate_mode']) && $company_payment_setting['coingate_mode'] == '' || isset($company_payment_setting['coingate_mode']) && $company_payment_setting['coingate_mode'] == 'sandbox' ? 'checked="checked"' : '' }}>{{__('Sandbox')}}
                                                            </label>
                                                            <label class="btn btn-primary btn-sm {{isset($company_payment_setting['coingate_mode']) && $company_payment_setting['coingate_mode'] == 'live' ? 'active' : ''}}">
                                                                <input type="radio" name="coingate_mode" value="live" {{ isset($company_payment_setting['coingate_mode']) && $company_payment_setting['coingate_mode'] == 'live' ? 'checked="checked"' : '' }}>{{__('Live')}}
                                                            </label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="coingate_auth_token" class="form-control-label">{{ __('CoinGate Auth Token') }}</label>
                                                            <input type="text" name="coingate_auth_token" id="coingate_auth_token" class="form-control" value="{{ isset($company_payment_setting['coingate_auth_token'])?$company_payment_setting['coingate_auth_token']:''}}" placeholder="{{ __('CoinGate Auth Token') }}"/>
                                                            @if($errors->has('coingate_auth_token'))
                                                                <span class="invalid-feedback d-block">
                                                                    {{ $errors->first('coingate_auth_token') }}
                                                                </span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-lg-12  text-right">
                                    <input type="submit" value="{{__('Save Changes')}}" class="btn-submit text-white">
                                </div>
                                {{Form::close()}}
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
@endsection
