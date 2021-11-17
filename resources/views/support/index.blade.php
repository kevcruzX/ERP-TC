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
    <a href="{{ route('support.grid') }}" class="btn btn-xs btn-white btn-icon-only width-auto">
        <i class="fas fa-th-large"></i> {{__('Grid View')}}</span>
    </a>
    <a href="#" data-size="lg" data-url="{{ route('support.create') }}" data-toggle="tooltip" data-ajax-popup="true"  class="btn btn-xs btn-white btn-icon-only width-auto">
        <i class="fas fa-plus"></i> {{__('Create')}}
    </a>
@endsection
@section('filter')

@endsection
@section('content')
    <div class="card">
        <div class="table-responsive">
            <table class="table align-items-center dataTable">
                <thead>
                <tr>
                    <th scope="col">{{__('Created By')}}</th>
                    <th scope="col">{{__('Ticket')}}</th>
                    <th scope="col">{{__('Code')}}</th>
                    <th scope="col">{{__('Attachment')}}</th>
                    <th scope="col">{{__('Assign User')}}</th>
                    <th scope="col">{{__('Created At')}}</th>
                    <th scope="col" class="text-right">{{__('Action')}}</th>
                </tr>
                </thead>
                <tbody class="list">
                @foreach($supports as $support)

                    <tr>
                        <th scope="row">
                            <div class="media align-items-center">
                                <div>
                                    <div class="avatar-parent-child">
                                        <img alt="" class="avatar rounded-circle" @if(!empty($support->createdBy) && !empty($support->createdBy->avatar)) src="{{asset(Storage::url('uploads/avatar')).'/'.$support->createdBy->avatar}}" @else  src="{{asset(Storage::url('uploads/avatar')).'/avatar.png'}}" @endif>
                                        @if($support->replyUnread()>0)
                                            <span class="avatar-child avatar-badge bg-success"></span>
                                        @endif
                                    </div>
                                </div>
                                <div class="media-body ml-4">
                                    {{!empty($support->createdBy)?$support->createdBy->name:''}}
                                </div>
                            </div>
                        </th>
                        <th scope="row">
                            <div class="media align-items-center">
                                <div class="media-body">
                                    <a href="{{ route('support.reply',\Crypt::encrypt($support->id)) }}" class="name h6 mb-0 text-sm">{{$support->subject}}</a><br>
                                    @if($support->priority == 0)
                                        <span data-toggle="tooltip" data-title="{{__('Priority')}}" class="text-capitalize badge badge-primary rounded-pill badge-sm">   {{ __(\App\Support::$priority[$support->priority]) }}</span>
                                    @elseif($support->priority == 1)
                                        <span data-toggle="tooltip" data-title="{{__('Priority')}}" class="text-capitalize badge badge-info rounded-pill badge-sm">   {{ __(\App\Support::$priority[$support->priority]) }}</span>
                                    @elseif($support->priority == 2)
                                        <span data-toggle="tooltip" data-title="{{__('Priority')}}" class="text-capitalize badge badge-warning rounded-pill badge-sm">   {{ __(\App\Support::$priority[$support->priority]) }}</span>
                                    @elseif($support->priority == 3)
                                        <span data-toggle="tooltip" data-title="{{__('Priority')}}" class="text-capitalize badge badge-danger rounded-pill badge-sm">   {{ __(\App\Support::$priority[$support->priority]) }}</span>
                                    @endif
                                </div>
                            </div>
                        </th>
                        <td>{{$support->ticket_code}}</td>
                        <td>
                            @if(!empty($support->attachment))
                                <a href="{{asset(Storage::url('uploads/supports')).'/'.$support->attachment}}" download="" class="btn btn-sm btn-secondary btn-icon rounded-pill" target="_blank"><span class="btn-inner--icon"><i class="fas fa-download"></i></span></a>

                            @else
                                -
                            @endif
                        </td>

                        <td>{{!empty($support->assignUser)?$support->assignUser->name:'-'}}</td>
                        <td>{{\Auth::user()->dateFormat($support->created_at)}}</td>

                        <td class="text-right">
                            <div class="actions ml-3">
                                <a href="{{ route('support.reply',\Crypt::encrypt($support->id)) }}" data-title="{{__('Support Reply')}}" class="action-item" data-toggle="tooltip" data-original-title="{{__('Reply')}}">
                                    <i class="fas fa-reply"></i>
                                </a>
                                @if(\Auth::user()->type=='company' || \Auth::user()->id==$support->ticket_created)
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
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

