@extends('layouts.admin')

@section('page-title')
    {{ ucwords($client->name).__("'s Detail") }}
@endsection

@section('content')

    {{--Estimations--}}
    <div class="row">
        <div class="col-12">
            <h4 class="h5 font-weight-400 float-left">{{__('Estimations')}}</h4>
        </div>
        <div class="col">
            <div class="card p-4 mb-4">
                <h5 class="report-text gray-text mb-0">{{__('Total Estimate')}}</h5>
                <h5 class="report-text mb-0">{{ $cnt_estimation['total'] }} / {{$cnt_estimation['cnt_total']}}</h5>
            </div>
        </div>
        <div class="col">
            <div class="card p-4 mb-4">
                <h5 class="report-text gray-text mb-0">{{__('This Month Total Estimate')}}</h5>
                <h5 class="report-text mb-0">{{ $cnt_estimation['this_month'] }} / {{$cnt_estimation['cnt_this_month']}}</h5>
            </div>
        </div>
        <div class="col">
            <div class="card p-4 mb-4">
                <h5 class="report-text gray-text mb-0">{{__('This Week Total Estimate')}}</h5>
                <h5 class="report-text mb-0">{{ $cnt_estimation['this_week'] }} / {{$cnt_estimation['cnt_this_week']}}</h5>
            </div>
        </div>
        <div class="col">
            <div class="card p-4 mb-4">
                <h5 class="report-text gray-text mb-0">{{__('Last 30 Days Total Estimate')}}</h5>
                <h5 class="report-text mb-0">{{ $cnt_estimation['last_30days'] }} / {{$cnt_estimation['cnt_last_30days']}}</h5>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card bg-none">
                <div class="table-responsive">
                    <table class="table table-striped dataTable">
                        <thead>
                        <tr>
                            <th>{{__('Estimate')}}</th>
                            <th>{{__('Client')}}</th>
                            <th>{{__('Issue Date')}}</th>
                            <th>{{__('Value')}}</th>
                            <th>{{__('Status')}}</th>
                            @if(Auth::user()->type != 'Client')
                                <th width="250px">{{__('Action')}}</th>
                            @endif
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($estimations as $estimate)
                            <tr>
                                <td class="Id">
                                    @can('View Estimation')
                                        <a href="{{route('estimations.show',$estimate->id)}}"> <i class="fas fa-file-estimate"></i> {{ Auth::user()->estimateNumberFormat($estimate->estimation_id) }}</a>
                                    @else
                                        {{ Auth::user()->estimateNumberFormat($estimate->estimation_id) }}
                                    @endcan
                                </td>
                                <td>{{ $estimate->client->name }}</td>
                                <td>{{ Auth::user()->dateFormat($estimate->issue_date) }}</td>
                                <td>{{ Auth::user()->priceFormat($estimate->getTotal()) }}</td>
                                <td>
                                    @if($estimate->status == 0)
                                        <span class="badge badge-pill badge-primary">{{ __(\App\Estimation::$statues[$estimate->status]) }}</span>
                                    @elseif($estimate->status == 1)
                                        <span class="badge badge-pill badge-danger">{{ __(\App\Estimation::$statues[$estimate->status]) }}</span>
                                    @elseif($estimate->status == 2)
                                        <span class="badge badge-pill badge-warning">{{ __(\App\Estimation::$statues[$estimate->status]) }}</span>
                                    @elseif($estimate->status == 3)
                                        <span class="badge badge-pill badge-success">{{ __(\App\Estimation::$statues[$estimate->status]) }}</span>
                                    @elseif($estimate->status == 4)
                                        <span class="badge badge-pill badge-info">{{ __(\App\Estimation::$statues[$estimate->status]) }}</span>
                                    @endif
                                </td>
                                @if(Auth::user()->type != 'Client')
                                    <td class="Action">
                                        <span>
                                        @can('View Estimation')
                                                <a href="{{route('estimations.show',$estimate->id)}}" class="edit-icon bg-warning" data-toggle="tooltip" data-original-title="{{ __('View') }}"><i class="fas fa-eye"></i></a>
                                            @endcan
                                            @can('Edit Estimation')
                                                <a href="#" data-url="{{ URL::to('estimations/'.$estimate->id.'/edit') }}" data-ajax-popup="true" data-title="{{__('Edit Estimation')}}" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="fas fa-pencil-alt"></i></a>
                                            @endcan
                                            @can('Delete Estimation')
                                                <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$estimate->id}}').submit();"><i class="fas fa-trash"></i></a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['estimations.destroy', $estimate->id],'id'=>'delete-form-'.$estimate->id]) !!}
                                                {!! Form::close() !!}
                                            @endif
                                        </span>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    {{--
    <div class="row">
        <div class="col-12">
            <h4 class="h5 font-weight-400 float-left">{{__('Contracts')}}</h4>
        </div>
        <div class="col">
            <div class="card p-4 mb-4">
                <h5 class="report-text gray-text mb-0">{{__('Total Contracts')}}</h5>
                <h5 class="report-text mb-0">{{ $cnt_contract['total'] }} / {{$cnt_contract['cnt_total']}}</h5>
            </div>
        </div>
        <div class="col">
            <div class="card p-4 mb-4">
                <h5 class="report-text gray-text mb-0">{{__('This Month Total Contracts')}}</h5>
                <h5 class="report-text mb-0">{{ $cnt_contract['this_month'] }} / {{$cnt_contract['cnt_this_month']}}</h5>
            </div>
        </div>
        <div class="col">
            <div class="card p-4 mb-4">
                <h5 class="report-text gray-text mb-0">{{__('This Week Total Contracts')}}</h5>
                <h5 class="report-text mb-0">{{ $cnt_contract['this_week'] }} / {{$cnt_contract['cnt_this_week']}}</h5>
            </div>
        </div>
        <div class="col">
            <div class="card p-4 mb-4">
                <h5 class="report-text gray-text mb-0">{{__('Last 30 Days Total Contracts')}}</h5>
                <h5 class="report-text mb-0">{{ $cnt_contract['last_30days'] }} / {{$cnt_contract['cnt_last_30days']}}</h5>
            </div>
        </div>

        <div class="col-md-12">
            <div class="card bg-none">
                <div class="table-responsive">
                    <table class="table table-striped dataTable">
                        <thead>
                        <tr>
                            <th>{{__('Name')}}</th>
                            <th>{{__('Client Name')}}</th>
                            <th>{{__('Value')}}</th>
                            <th>{{__('Type')}}</th>
                            <th>{{__('Start Date')}}</th>
                            <th>{{__('End Date')}}</th>
                            <th>{{__('Status')}}</th>
                            <th width="250px">{{__('Action')}}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($contracts as $contract)
                            <tr>
                                <td>{{ $contract->name }}</td>
                                <td>{{ $contract->client->name }}</td>
                                <td>{{ Auth::user()->priceFormat($contract->value) }}</td>
                                <td>{{ $contract->contract_type->name }}</td>
                                <td>{{ Auth::user()->dateFormat($contract->start_date) }}</td>
                                <td>{{ Auth::user()->dateFormat($contract->end_date) }}</td>
                                <td>{{ $contract->status }}</td>
                                <td class="Action">
                                    <span>
                                    @can('Edit Contract')
                                            <a href="#" data-url="{{ URL::to('contract/'.$contract->id.'/edit') }}" data-size="lg" data-ajax-popup="true" data-title="{{__('Edit Contract Type')}}" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="fas fa-pencil-alt"></i></a>
                                        @endcan
                                        @can('Delete Contract')
                                            <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$contract->id}}').submit();"><i class="fas fa-trash"></i></a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => ['contract.destroy', $contract->id],'id'=>'delete-form-'.$contract->id]) !!}
                                            {!! Form::close() !!}
                                        @endif
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    Contracts--}}
@endsection
