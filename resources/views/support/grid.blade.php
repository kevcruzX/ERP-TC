@extends('layouts.admin')
@push('script-page')
@endpush
@section('page-title')
    {{__('Support')}}
@endsection
@section('title')
    <div class="d-inline-block">
        <h5 class="h4 d-inline-block font-weight-400 mb-0 ">{{__('Support')}}</h5>
    </div>
@endsection
@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{route('dashboard')}}">{{__('Dashboard')}}</a></li>
    <li class="breadcrumb-item active" aria-current="page">{{__('Support')}}</li>
@endsection
@section('action-button')
    <a href="{{ route('support.index') }}" class="btn btn-xs btn-white btn-icon-only width-auto">
        <i class="fas fa-list"></i> {{__('List View')}}</span>
    </a>
    <a href="#" data-size="lg" data-url="{{ route('support.create') }}" data-toggle="tooltip" data-ajax-popup="true" data-title="{{__('Create New Support')}}" class="btn btn-xs btn-white btn-icon-only width-auto">
        <i class="fas fa-plus"></i> {{__('Create')}}
    </a>
@endsection
@section('filter')
@endsection
@section('content')
    <div class="row">
        @foreach($supports as $support)
            <div class="col-md-3">
                <div class="card card-fluid">
                    <div class="card-header">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <a href="#" class="avatar rounded-circle">
                                    <img alt="" class="" @if(!empty($support->createdBy) && !empty($support->createdBy->avatar)) src="{{asset(Storage::url('uploads/avatar')).'/'.$support->createdBy->avatar}}" @else  src="{{asset(Storage::url('uploads/avatar')).'/avatar.png'}}" @endif>
                                    @if($support->replyUnread()>0)
                                        <span class="avatar-child avatar-badge bg-success"></span>
                                    @endif
                                </a>
                            </div>
                            <div class="col">
                                <a href="#!" class="d-block h6 mb-0">{{!empty($support->createdBy)?$support->createdBy->name:''}}</a>
                                <small class="d-block text-muted">{{$support->subject}}</small>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">

                            <div class="col text-center">
                                <span class="h6 mb-0">{{$support->ticket_code}}</span>
                                <span class="d-block text-sm">{{__('Code')}}</span>
                            </div>
                            <div class="col text-center">
                                <span class="h6 mb-0">
                                     @if($support->priority == 0)
                                        <span  class="text-capitalize badge badge-primary rounded-pill badge-sm">   {{ __(\App\Support::$priority[$support->priority]) }}</span>
                                    @elseif($support->priority == 1)
                                        <span  class="text-capitalize badge badge-info rounded-pill badge-sm">   {{ __(\App\Support::$priority[$support->priority]) }}</span>
                                    @elseif($support->priority == 2)
                                        <span  class="text-capitalize badge badge-warning rounded-pill badge-sm">   {{ __(\App\Support::$priority[$support->priority]) }}</span>
                                    @elseif($support->priority == 3)
                                        <span  class="text-capitalize badge badge-danger rounded-pill badge-sm">   {{ __(\App\Support::$priority[$support->priority]) }}</span>
                                    @endif
                                </span>
                                <span class="d-block text-sm">{{__('Priority')}}</span>
                            </div>
                            <div class="col text-center">
                                <span class="h6 mb-0">
                                    @if(!empty($support->attachment))
                                        <a href="{{asset(Storage::url('uploads/supports')).'/'.$support->attachment}}" download="" class="btn btn-sm btn-secondary btn-icon rounded-pill" target="_blank"><span class="btn-inner--icon"><i class="fas fa-download"></i></span></a>

                                    @else
                                        -
                                    @endif
                                </span>

                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="row align-items-center">
                            <div class="col-auto">
                                <a href="#" data-toggle="tooltip" data-title="{{__('Created Date')}}">{{\Auth::user()->dateFormat($support->created_at)}}</a>
                            </div>
                            <div class="col text-right">
                                <a href="{{ route('support.reply',\Crypt::encrypt($support->id)) }}" data-title="{{__('Support Reply')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Reply')}}">
                                    <i class="fas fa-reply"></i>
                                </a>
                                @if(\Auth::user()->id==$support->ticket_created)
                                    <a href="#" data-size="lg" data-url="{{ route('support.edit',$support->id) }}" data-ajax-popup="true" data-title="{{__('Edit Support')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                        <i class="far fa-edit"></i>
                                    </a>
                                    <a href="#!" class="action-item" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="Are You Sure?|This action can not be undone. Do you want to continue?" data-confirm-yes="document.getElementById('delete-form-{{$support->id}}').submit();">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                    {!! Form::open(['method' => 'DELETE', 'route' => ['support.destroy', $support->id],'id'=>'delete-form-'.$support->id]) !!}
                                    {!! Form::close() !!}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

