@extends('layouts.admin')

@section('page-title')
    {{__('Manage Deals')}} @if($pipeline) - {{$pipeline->name}} @endif
@endsection

@push('css-page')
    <link rel="stylesheet" href="{{asset('assets/libs/summernote/summernote-bs4.css')}}">
@endpush

@push('script-page')
    <script src="{{asset('assets/libs/summernote/summernote-bs4.js')}}"></script>
    <script>
        $(document).on("change", "#change-pipeline select[name=default_pipeline_id]", function () {
            $('#change-pipeline').submit();
        });
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
        @can('Create Deal')
            <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 col-12">
                <div class="all-button-box">
                    <a href="#" data-url="{{ route('deals.create') }}" data-ajax-popup="true" data-size="lg" data-title="{{__('Create Deal')}}" class="btn btn-xs btn-white btn-icon-only width-auto"><i class="fas fa-plus"></i> {{__('Create')}}</a>
                </div>
            </div>
        @endcan
        <div class="col-xl-2 col-lg-3 col-md-4 col-sm-12 col-12">
            <div class="all-button-box">
                <a href="{{ route('deals.index') }}" class="btn btn-xs btn-white btn-icon-only width-auto"><i class="fas fa-table"></i> {{__('Kanban View')}} </a>
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
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body py-0">
                        <div class="table-responsive">
                            <table class="table table-striped dataTable">
                                <thead>
                                <tr>
                                    <th>{{__('Name')}}</th>
                                    <th>{{__('Price')}}</th>
                                    <th>{{__('Stage')}}</th>
                                    <th>{{__('Tasks')}}</th>
                                    <th>{{__('Users')}}</th>
                                    @if(Gate::check('Edit Deal') ||  Gate::check('Delete Deal'))
                                        <th width="300px">{{__('Action')}}</th>
                                    @endif
                                </tr>
                                </thead>
                                <tbody>
                                @if(count($deals) > 0)
                                    @foreach ($deals as $deal)
                                        <tr>
                                            <td>{{ $deal->name }}</td>
                                            <td>{{\Auth::user()->priceFormat($deal->price)}}</td>
                                            <td>{{ $deal->stage->name }}</td>
                                            <td>{{count($deal->tasks)}}/{{count($deal->complete_tasks)}}</td>
                                            <td>
                                                @foreach($deal->users as $user)
                                                    <a href="#" class="btn btn-sm mr-1 p-0 rounded-circle">
                                                        <img alt="image" data-toggle="tooltip" data-original-title="{{$user->name}}" @if($user->avatar) src="{{asset('/storage/uploads/avatar/'.$user->avatar)}}" @else src="{{asset('assets/img/avatar/avatar-1.png')}}" @endif class="rounded-circle " width="25" height="25">
                                                    </a>
                                                @endforeach
                                            </td>
                                            @if(\Auth::user()->type != 'Client')
                                                <td class="Action">
                                                    <span>
                                                    @can('View Deal')
                                                            @if($deal->is_active)
                                                                <a href="{{route('deals.show',$deal->id)}}" class="bg-warning edit-icon"><i class="fas fa-eye"></i></a>
                                                            @endif
                                                        @endcan
                                                        @can('Edit Deal')
                                                            <a href="#" data-url="{{ URL::to('deals/'.$deal->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Deal')}}" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="fas fa-pencil-alt"></i></a>
                                                        @endcan
                                                        @can('Delete Deal')
                                                            <a href="#" data-title="{{__('Delete Deal')}}" class="delete-icon" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$deal->id}}').submit();"><i class="fas fa-trash"></i></a>
                                                            {!! Form::open(['method' => 'DELETE', 'route' => ['deals.destroy', $deal->id],'id'=>'delete-form-'.$deal->id]) !!}
                                                            {!! Form::close() !!}
                                                        @endif
                                                    </span>
                                                </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                @else
                                    <tr class="font-style">
                                        <td colspan="6" class="text-center">{{ __('No data available in table') }}</td>
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

@endsection
