@extends('layouts.admin')

@section('page-title')
    {{ __('Manage Clients') }}
@endsection

@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
        @can('create client')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" class="btn btn-xs btn-white btn-icon-only width-auto" data-ajax-popup="true" data-size="lg" data-title="{{__('Create Client')}}" data-url="{{route('clients.create')}}"><i class="fas fa-plus"></i> {{__('Add')}} </a>
            </div>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row mt-0">
        @foreach($clients as $client)
            <div class="col-lg-3 col-sm-6 col-md-4">
                <div class="card profile-card">
                    <div class="edit-profile user-text">
                        <div class="dropdown action-item">
                            @if($client->is_active == 1)
                                <a href="#" class="action-item" role="button" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></a>
                                <div class="dropdown-menu dropdown-menu-right">
                                    <a href="{{route('clients.show',$client->id)}}" class="dropdown-item text-sm">{{__('View')}}</a>
                                    @can('edit client')
                                        <a href="#" class="dropdown-item text-sm" data-url="{{route('clients.edit',$client->id)}}" data-ajax-popup="true" data-title="{{__('Edit Client')}}">{{__('Edit')}}</a>
                                    @endcan
                                    @can('delete client')
                                        <a class="dropdown-item text-sm" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$client->id}}').submit();">{{__('Delete')}}</a>
                                        {!! Form::open(['method' => 'DELETE', 'route' => ['clients.destroy', $client->id],'id'=>'delete-form-'.$client->id]) !!}
                                        {!! Form::close() !!}
                                    @endcan
                                </div>
                            @else
                                <a href="#" class="action-item"><i class="fas fa-lock"></i></a>
                            @endif
                        </div>
                    </div>
                    <div class="avatar-parent-child">
                        <img @if($client->avatar) src="{{asset(Storage::url("uploads/avatar/".$client->avatar))}}" @else src="{{asset('assets/img/avatar/avatar-1.png')}}" @endif class="avatar rounded-circle avatar-xl">
                    </div>
                    <h4 class="h4 mb-0 mt-2"><a href="{{route('clients.show',$client->id)}}">{{$client->name}}</a></h4>
                    <div class="sal-right-card">
                        <span class="badge badge-pill badge-blue">{{$client->email}}</span>
                    </div>
                    <div class="office-time mb-0 mt-3">
                        <div class="row">
                            <div class="col-6">
                                <div class="font-weight-bold text-sm">{{__('Deals')}}</div>
                                @if($client->clientDeals)
                                {{$client->clientDeals->count()}}
                                @endif
                            </div>
                            <div class="col-6">
                                <div class="font-weight-bold text-sm">{{__('Projects')}}</div>
                                @if($client->clientProjects)
                                {{ $client->clientProjects->count() }}
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
