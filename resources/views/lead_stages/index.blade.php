@extends('layouts.admin')

@section('page-title')
    {{__('Manage Lead Stages')}}
@endsection

@push('script-page')
    <script src="{{ asset('assets/libs/jquery-ui/jquery-ui.js') }}"></script>
    <script>
        $(function () {
            $(".sortable").sortable();
            $(".sortable").disableSelection();
            $(".sortable").sortable({
                stop: function () {
                    var order = [];
                    $(this).find('li').each(function (index, data) {
                        order[index] = $(data).attr('data-id');
                    });

                    $.ajax({
                        url: "{{route('lead_stages.order')}}",
                        data: {order: order, _token: $('meta[name="csrf-token"]').attr('content')},
                        type: 'POST',
                        success: function (data) {
                        },
                        error: function (data) {
                            data = data.responseJSON;
                            show_toastr('Error', data.error, 'error')
                        }
                    })
                }
            });
        });
    </script>
@endpush

@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
        @can('create lead stage')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-url="{{ route('lead_stages.create') }}" data-ajax-popup="true" data-title="{{__('Create Lead Stage')}}" class="btn btn-xs btn-white btn-icon-only width-auto"><i class="fas fa-plus"></i> {{__('Create')}} </a>
            </div>
        @endcan
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-lg-12">
            <ul class="nav nav-tabs my-3">
                @php($i=0)
                @foreach($pipelines as $key => $pipeline)
                    <li class="nav-item ml-0 mr-0">
                        <a class="@if($i==0) active @endif" data-toggle="tab" href="#tab{{$key}}" role="tab" aria-controls="home" aria-selected="true">{{$pipeline['name']}}</a>
                    </li>
                    @php($i++)
                @endforeach
            </ul>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="tab-content tab-bordered">
                        @php($i=0)
                        @foreach($pipelines as $key => $pipeline)
                            <div class="tab-pane fade show @if($i==0) active @endif" id="tab{{$key}}" role="tabpanel">
                                <ul class="list-group sortable">
                                    @foreach ($pipeline['lead_stages'] as $lead_stages)
                                        <li class="list-group-item" data-id="{{$lead_stages->id}}">
                                            <span class="text-xs text-dark">{{$lead_stages->name}}</span>
                                            <span class="float-right">
                                                @can('edit lead stage')
                                                    <a href="#" data-url="{{ URL::to('lead_stages/'.$lead_stages->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Lead Stages')}}" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                @endcan
                                                @if(count($pipeline['lead_stages']))
                                                    @can('delete lead stage')
                                                        <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$lead_stages->id}}').submit();"><i class="fas fa-trash"></i></a>
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['lead_stages.destroy', $lead_stages->id],'id'=>'delete-form-'.$lead_stages->id]) !!}
                                                        {!! Form::close() !!}
                                                    @endcan
                                                @endif
                                            </span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            @php($i++)
                        @endforeach
                    </div>
                    <p class="text-muted mt-4"><strong>{{__('Note')}} : </strong>{{__('You can easily change order of lead stage using drag & drop.')}}</p>
                </div>

            </div>
        </div>
    </div>
@endsection
