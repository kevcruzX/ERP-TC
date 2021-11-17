@extends('layouts.admin')

@section('page-title')
    {{__('Manage Deals')}} @if($pipeline) - {{$pipeline->name}} @endif
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{asset('assets/libs/summernote/summernote-bs4.css')}}">
@endpush

@push('script-page')
    <script src="{{asset('assets/libs/summernote/summernote-bs4.js')}}"></script>
    @can("move deal")
        @if($pipeline)
            <script src="{{asset('assets/libs/dragula/dist/dragula.min.js')}}"></script>
            <script>
                !function (a) {
                    "use strict";
                    var t = function () {
                        this.$body = a("body")
                    };
                    t.prototype.init = function () {
                        a('[data-plugin="dragula"]').each(function () {
                            var t = a(this).data("containers"), n = [];
                            if (t) for (var i = 0; i < t.length; i++) n.push(a("#" + t[i])[0]); else n = [a(this)[0]];
                            var r = a(this).data("handleclass");
                            r ? dragula(n, {
                                moves: function (a, t, n) {
                                    return n.classList.contains(r)
                                }
                            }) : dragula(n).on('drop', function (el, target, source, sibling) {

                                var order = [];
                                $("#" + target.id + " > div").each(function () {
                                    order[$(this).index()] = $(this).attr('data-id');
                                });

                                var id = $(el).attr('data-id');

                                var old_status = $("#" + source.id).data('status');
                                var new_status = $("#" + target.id).data('status');
                                var stage_id = $(target).attr('data-id');
                                var pipeline_id = '{{$pipeline->id}}';

                                $("#" + source.id).parent().find('.count').text($("#" + source.id + " > div").length);
                                $("#" + target.id).parent().find('.count').text($("#" + target.id + " > div").length);
                                $.ajax({
                                    url: '{{route('deals.order')}}',
                                    type: 'POST',
                                    data: {deal_id: id, stage_id: stage_id, order: order, new_status: new_status, old_status: old_status, pipeline_id: pipeline_id, "_token": $('meta[name="csrf-token"]').attr('content')},
                                    success: function (data) {
                                    },
                                    error: function (data) {
                                        data = data.responseJSON;
                                        show_toastr('Error', data.error, 'error')
                                    }
                                });
                            });
                        })
                    }, a.Dragula = new t, a.Dragula.Constructor = t
                }(window.jQuery), function (a) {
                    "use strict";

                    a.Dragula.init()

                }(window.jQuery);
            </script>
        @endif
    @endcan
    <script>
        $(document).on("change", "#change-pipeline select[name=default_pipeline_id]", function () {
            $('#change-pipeline').submit();
        })
    </script>
@endpush

@section('action-button')
    <div class="row d-flex justify-content-end">
        @if($pipeline)
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 col-12">
                <div class="all-select-box">
                    <div class="btn-box">
                        {{ Form::open(array('route' => 'deals.change.pipeline','id'=>'change-pipeline','class'=>'mr-2')) }}
                        {{ Form::select('default_pipeline_id', $pipelines,$pipeline->id, array('class' => 'form-control select2','id'=>'default_pipeline_id')) }}
                        {{ Form::close() }}
                    </div>
                </div>
            </div>
        @endif
        @can('create deal')
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 col-12">
                <div class="all-button-box">
                    <a href="#" data-url="{{ route('deals.create') }}" data-ajax-popup="true" data-size="lg" data-title="{{__('Create Deal')}}" class="btn btn-xs btn-white btn-icon-only width-auto"><i class="fas fa-plus"></i> {{__('Create')}}</a>
                </div>
            </div>
        @endcan
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 col-12">
            <div class="all-button-box">
                <a href="{{ route('deals.list') }}" class="btn btn-xs btn-white btn-icon-only width-auto"><i class="fas fa-list"></i> {{__('List View')}} </a>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @if($pipeline)
        <div class="row">
            <div class="col">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Total Deals')}}</h5>
                    <h5 class="report-text mb-0">{{ $cnt_deal['total'] }}</h5>
                </div>
            </div>
            <div class="col">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('This Month Total Deals')}}</h5>
                    <h5 class="report-text mb-0">{{ $cnt_deal['this_month'] }}</h5>
                </div>
            </div>
            <div class="col">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('This Week Total Deals')}}</h5>
                    <h5 class="report-text mb-0">{{ $cnt_deal['this_week'] }}</h5>
                </div>
            </div>
            <div class="col">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Last 30 Days Total Deals')}}</h5>
                    <h5 class="report-text mb-0">{{ $cnt_deal['last_30days'] }}</h5>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                @php
                    $stages = $pipeline->stages;

                    $json = [];
                    foreach ($stages as $stage){
                        $json[] = 'task-list-'.$stage->id;
                    }
                @endphp
                <div class="board" data-plugin="dragula" data-containers='{!! json_encode($json) !!}'>

                    @foreach($stages as $stage)

                        @php($deals = $stage->deals())

                        <div class="tasks mb-2">
                            <h5 class="mt-0 mb-0 task-header">{{$stage->name}} (<span class="count">{{count($deals)}}</span>)</h5>
                            <div id="task-list-{{$stage->id}}" data-id="{{$stage->id}}" class="task-list-items">
                                @foreach($deals as $deal)
                                    <div class="card mb-2 mt-0" data-id="{{$deal->id}}">
                                        <div class="card-body p-0 deal_title">
                                            @if(Auth::user()->type != 'Client')
                                                <div class="float-right">
                                                    @if(!$deal->is_active)
                                                        <div class="dropdown">
                                                            <a href="#" class="action-item" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-lock"></i></a>
                                                        </div>
                                                    @else
                                                        <div class="dropdown global-icon pr-1">
                                                            <a href="#" class="action-item" data-toggle="dropdown" aria-expanded="false"><i class="fas fa-ellipsis-h"></i></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                @can('edit deal')
                                                                    <a href="#" data-url="{{ URL::to('deals/'.$deal->id.'/labels') }}" data-ajax-popup="true" data-title="{{__('Labels')}}" class="dropdown-item">{{__('Labels')}}</a>
                                                                    <a href="#" data-url="{{ URL::to('deals/'.$deal->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Deal')}}" class="dropdown-item">{{__('Edit')}}</a>
                                                                @endcan
                                                                @can('delete deal')
                                                                    <a href="#" data-title="{{__('Delete Deal')}}" class="dropdown-item" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$deal->id}}').submit();">{{__('Delete')}}</a>
                                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['deals.destroy', $deal->id],'id'=>'delete-form-'.$deal->id]) !!}
                                                                    {!! Form::close() !!}
                                                                @endcan
                                                            </div>
                                                        </div>
                                                    @endif
                                                </div>
                                            @endif
                                            <div class="pl-2 pt-0 pr-2 pb-2">
                                                <h6>
                                                    @php($labels = $deal->labels())
                                                    @if($labels)
                                                        @foreach($labels as $label)
                                                            <span class="badge badge-{{$label->color}} mr-1" data-toggle="tooltip" data-original-title="{{$label->name}}"> </span>
                                                        @endforeach
                                                    @endif
                                                </h6>
                                                <h5 class="mt-2 mb-2">
                                                    <a href="@can('view deal') @if($deal->is_active) {{route('deals.show',$deal->id)}} @else # @endif @else # @endcan" class="text-body">{{$deal->name}} <span class="deal_icon"><i class="fas fa-eye"></i></span></a>
                                                </h5>
                                                <p class="mb-0">
                                                    <span class="text-nowrap mb-2 d-inline-block text-xs">
                                                        <b>{{count($deal->tasks)}}/{{count($deal->complete_tasks)}}</b> {{__('Tasks')}}
                                                    </span>
                                                </p>
                                                <div class="float-right font-weight-bold mt-1 text-sm">{{\Auth::user()->priceFormat($deal->price)}}</div>
                                                <p class="mb-0">
                                                    @foreach($deal->users as $user)
                                                        <a href="#" class="btn btn-sm mr-1 p-0 rounded-circle">
                                                            <img alt="image" data-toggle="tooltip" data-original-title="{{$user->name}}" @if($user->avatar) src="{{asset('/storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('assets/img/avatar/avatar-1.png')}}" @endif class="rounded-circle " width="25" height="25">
                                                        </a>
                                                    @endforeach
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
@endsection
