@extends('layouts.auth')
@section('page-title')
    {{__('Verify Email')}}
@endsection
@php
    $logo=asset(Storage::url('uploads/logo/'));
 $company_logo=Utility::getValByName('company_logo');
@endphp

@section('content')
    <div class="login-contain">
        <div class="login-inner-contain">
            <a class="navbar-brand" href="#">
                <img src="{{$logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo.png')}}" class="navbar-brand-img big-logo" alt="logo">
            </a>
            <div class="login-form">
                <div class="page-title"><h5>{{__('Verify Your Email Address')}}</h5></div>
                @if (session('resent'))
                    <small class="text-success">{{ __('A fresh verification link has been sent to your email address.') }}</small>
                @endif

                <small class="text-muted">{{ __('Before proceeding, please check your email for a verification link.') }}
                    {{ __('If you did not receive the email') }},</small> <small><a href="{{ route('verification.resend') }}" class="text-primary">{{ __('click here to request another') }}</a>.</small>
            </div>
        </div>
    </div>
@endsection
