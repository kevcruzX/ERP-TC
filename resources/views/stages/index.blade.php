@extends('layouts.admin')

@section('page-title')
    {{__('Manage Deal Stages')}}
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
                        url: "{{route('stages.order')}}",
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
        @can('create stage')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-url="{{ route('stages.create') }}" data-ajax-popup="true" data-title="{{__('Create Deal Stage')}}" class="btn btn-xs btn-white btn-icon-only width-auto"><i class="fas fa-plus"></i> {{__('Create')}} </a>
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
                    <li>
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
                                    @foreach ($pipeline['stages'] as $stage)
                                        <li class="list-group-item" data-id="{{$stage->id}}">
                                            <span class="text-xs text-dark">{{$stage->name}}</span>
                                            <span class="float-right">
                                                @can('edit stage')
                                                    <a href="#" data-url="{{ URL::to('stages/'.$stage->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Deal Stages')}}" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                @endcan
                                                @if(count($pipeline['stages']))
                                                    @can('delete stage')
                                                        <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$stage->id}}').submit();"><i class="fas fa-trash"></i></a>
                                                        {!! Form::open(['method' => 'DELETE', 'route' => ['stages.destroy', $stage->id],'id'=>'delete-form-'.$stage->id]) !!}
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
                    <p class="text-muted mt-4"><strong>{{__('Note')}} : </strong>{{__('You can easily change order of deal stage using drag & drop.')}}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
