@extends('layouts.admin')

@section('page-title')
    {{__('Manage Labels')}}
@endsection

@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
        @can('create label')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-url="{{ route('labels.create') }}" data-ajax-popup="true" data-title="{{__('Create Label')}}" class="btn btn-xs btn-white btn-icon-only width-auto"><i class="fas fa-plus"></i> {{__('Create')}} </a>
            </div>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            @if($pipelines)
                <ul class="nav nav-tabs my-3">
                    @php($i=0)
                    @foreach($pipelines as $key => $pipeline)
                        <li class="nav-item">
                            <a class="@if($i==0) active @endif" data-toggle="tab" href="#tab{{$key}}" role="tab" aria-controls="home" aria-selected="true">{{$pipeline['name']}}</a>
                        </li>
                        @php($i++)
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="col-md-12">
            @if($pipelines)
                <div class="card">
                    <div class="card-body">
                        <div class="tab-content tab-bordered">
                            @php($i=0)
                            @foreach($pipelines as $key => $pipeline)
                                <div class="tab-pane fade show @if($i==0) active @endif" id="tab{{$key}}" role="tabpanel">
                                    <ul class="list-group sortable">
                                        @foreach ($pipeline['labels'] as $label)
                                            <li class="list-group-item" data-id="{{$label->id}}">
                                                <div class="badge badge-pill badge-{{$label->color}}">{{$label->name}}</div>
                                                <span class="float-right">
                                                    @can('edit label')
                                                        <a href="#" data-url="{{ URL::to('labels/'.$label->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Labels')}}" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="fas fa-pencil-alt"></i></a>
                                                    @endcan
                                                    @can('delete label')
                                                        <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$label->id}}').submit();"><i class="fas fa-trash"></i></a>
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['labels.destroy', $label->id],'id'=>'delete-form-'.$label->id]) !!}
                                                        {!! Form::close() !!}
                                                    @endif
                                        </span>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @php($i++)
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection
