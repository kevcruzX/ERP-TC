@extends('layouts.auth')
@section('page-title')
    {{__('Forgot Password')}}
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
                <div class="page-title"><h5><span>{{__('Forgot')}}</span> {{__('Password')}}</h5></div>
                <small class="text-muted"></small>
                @if (session('status'))
                    <small class="text-muted">{{ session('status') }}</small>
                @endif
                <form method="POST" action="{{ route('customer.password.email') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-control-label" for="email">{{ __('E-Mail Address') }}</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                    <button type="submit" class="btn-login">{{ __('Send Password Reset Link') }}</button>
                    <div class="or-text">{{__('OR')}}</div>
                    <a href="{{ route('customer.login') }}" class="text-xs text-primary">{{__('Login')}}</a>
                </form>
            </div>
            <h5 class="copyright-text">
                {{(Utility::getValByName('footer_text')) ? Utility::getValByName('footer_text') :  __('Copyright HRMGo') }} {{ date('Y') }}
            </h5>

            <div class="all-select">
                <a href="#" class="monthly-btn">
                    <span class="monthly-text py-0">{{__('Change Language')}}</span>
                    <select class="select-box" onchange="this.options[this.selectedIndex].value && (window.location = this.options[this.selectedIndex].value);" id="language">
                        @foreach(Utility::languages() as $language)
                            <option @if($lang == $language) selected @endif value="{{ route('customer.change.langPass',$language) }}">{{Str::upper($language)}}</option>
                        @endforeach
                    </select>
                </a>
            </div>
        </div>
    </div>
@endsection
