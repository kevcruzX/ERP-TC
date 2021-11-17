@php
    $logo=asset(Storage::url('logo/'));
    $company_logo=Utility::getValByName('company_logo');
    $favicon=Utility::getValByName('company_favicon');
@endphp

    <!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="CRMGo SaaS - Projects, Accounting, Leads, Deals & HRM Tool">
    <meta name="author" content="Rajodiya Infotech">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{asset(Storage::url('uploads/logo/favicon.png'))}}" type="image" sizes="16x16">
    <title>{{__('Form')}} &dash; {{(Utility::getValByName('header_text')) ? Utility::getValByName('header_text') : config('app.name', 'LeadGo')}}
        {{(Utility::getValByName('header_text')) ? Utility::getValByName('header_text') : config('app.name', 'CRMGo')}}
    </title>
    <link rel="stylesheet" href="{{ asset('assets/libs/@fortawesome/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/libs/select2/dist/css/select2.min.css') }}">


    <link rel="stylesheet" href="{{ asset('assets/css/site.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/ac.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/stylesheet.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

</head>


<body class="application application-offset">
<div class="container-fluid container-application">
    <div class="main-content position-relative">
        <div class="page-content">
            <div class="min-vh-100 py-5 d-flex align-items-center">
                <div class="w-100">
                    <div class="row justify-content-center">
                        <div class="col-sm-8 col-lg-5">
                            <div class="row justify-content-center mb-3">
                                <a class="navbar-brand" href="#">
                                    <img src="{{asset(Storage::url('uploads/logo/logo.png'))}}" class="navbar-brand-img big-logo">
                                </a>
                            </div>
                            <div class="card shadow zindex-100 mb-0">
                                @if($form->is_active == 1)
                                    {{Form::open(array('route'=>array('form.view.store'),'method'=>'post'))}}
                                    <div class="card-body px-md-5 py-5">
                                        <div class="mb-4">
                                            <h6 class="h3">{{$form->name}}</h6>
                                        </div>
                                        <input type="hidden" value="{{$code}}" name="code">
                                        @if($objFields && $objFields->count() > 0)
                                            @foreach($objFields as $objField)
                                                @if($objField->type == 'text')
                                                    <div class="form-group">
                                                        {{ Form::label('field-'.$objField->id, __($objField->name),['class'=>'form-control-label']) }}
                                                        {{ Form::text('field['.$objField->id.']', null, array('class' => 'form-control','required'=>'required','id'=>'field-'.$objField->id)) }}
                                                    </div>
                                                @elseif($objField->type == 'email')
                                                    <div class="form-group">
                                                        {{ Form::label('field-'.$objField->id, __($objField->name),['class'=>'form-control-label']) }}
                                                        {{ Form::email('field['.$objField->id.']', null, array('class' => 'form-control','required'=>'required','id'=>'field-'.$objField->id)) }}
                                                    </div>
                                                @elseif($objField->type == 'number')
                                                    <div class="form-group">
                                                        {{ Form::label('field-'.$objField->id, __($objField->name),['class'=>'form-control-label']) }}
                                                        {{ Form::number('field['.$objField->id.']', null, array('class' => 'form-control','required'=>'required','id'=>'field-'.$objField->id)) }}
                                                    </div>
                                                @elseif($objField->type == 'date')
                                                    <div class="form-group">
                                                        {{ Form::label('field-'.$objField->id, __($objField->name),['class'=>'form-control-label']) }}
                                                        {{ Form::date('field['.$objField->id.']', null, array('class' => 'form-control','required'=>'required','id'=>'field-'.$objField->id)) }}
                                                    </div>
                                                @elseif($objField->type == 'textarea')
                                                    <div class="form-group">
                                                        {{ Form::label('field-'.$objField->id, __($objField->name),['class'=>'form-control-label']) }}
                                                        {{ Form::textarea('field['.$objField->id.']', null, array('class' => 'form-control','required'=>'required','id'=>'field-'.$objField->id)) }}
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                        <div class="mt-4">
                                            {{Form::submit(__('Submit'),array('class'=>'btn btn-sm btn-primary btn-icon rounded-pill'))}}
                                        </div>
                                    </div>

                                    {{Form::close()}}
                                @else
                                    <div class="page-title"><h5>{{__('Form is not active.')}}</h5></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('assets/js/site.core.js')}}"></script>
<script src="{{ asset('assets/js/site.js')}}"></script>
<script src="{{ asset('assets/libs/bootstrap-notify/bootstrap-notify.min.js')}}"></script>
<script src="{{ asset('assets/js/demo.js')}}"></script>
<script>
    function toastrs(title, message, type) {
        var o, i;
        var icon = '';
        var cls = '';
        if (type == 'success') {
            icon = 'fas fa-check-circle';
            cls = 'success';
        } else {
            icon = 'fas fa-times-circle';
            cls = 'danger';
        }
        $.notify({icon: icon, title: " " + title, message: message, url: ""}, {
            element: "body",
            type: cls,
            allow_dismiss: !0,
            placement: {from: 'top', align: 'right'},
            offset: {x: 15, y: 15},
            spacing: 10,
            z_index: 1080,
            delay: 2500,
            timer: 2000,
            url_target: "_blank",
            mouse_over: !1,
            animate: {enter: o, exit: i},
            template: '<div class="alert alert-{0} alert-icon alert-group alert-notify" data-notify="container" role="alert"><div class="alert-group-prepend alert-content"><span class="alert-group-icon"><i data-notify="icon"></i></span></div><div class="alert-content"><strong data-notify="title">{1}</strong><div data-notify="message">{2}</div></div><button type="button" class="close" data-notify="dismiss" aria-label="Close"><span aria-hidden="true">&times;</span></button></div>'
        });
    }
</script>
@if(Session::has('success'))
    <script>
        toastrs('{{__('Success')}}', '{!! session('success') !!}', 'success');
    </script>
    {{ Session::forget('success') }}
@endif
@if(Session::has('error'))
    <script>
        toastrs('{{__('Error')}}', '{!! session('error') !!}', 'error');
    </script>
    {{ Session::forget('error') }}
@endif
</body>
</html>
