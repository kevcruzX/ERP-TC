@extends('layouts.admin')

@section('page-title')
    {{__('Manage Holiday')}}
@endsection

@section('action-button')
    @can('create holiday')
        <div class="all-button-box row d-flex justify-content-end mt-2">
          <div class="col-auto">
              <div class="all-select-box">
                  <div class="btn-box">
                      {{Form::label('start_date',__('Start Date'),['class'=>'text-type'])}}
                      {{Form::text('start_date',isset($_GET['start_date'])?$_GET['start_date']:'',array('class'=>'month-btn form-control datepicker'))}}
                  </div>
              </div>
          </div>
          <div class="col-auto">
              <div class="all-select-box">
                  <div class="btn-box">
                      {{Form::label('end_date',__('End Date'),['class'=>'text-type'])}}
                      {{Form::text('end_date',isset($_GET['end_date'])?$_GET['end_date']:'',array('class'=>'month-btn form-control datepicker'))}}
                  </div>
              </div>
          </div>
          <div class="col-auto">
            {{ Form::open(array('route' => array('holiday.index'),'method'=>'get','id'=>'holiday_filter')) }}
          </div>
          <div class="col-auto my-custom">
              <a href="#" class="apply-btn" onclick="document.getElementById('holiday_filter').submit(); return false;" data-toggle="tooltip" data-original-title="{{__('apply')}}">
                  <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
              </a>
              <a href="{{route('holiday.index')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
                  <span class="btn-inner--icon"><i class="fas fa-trash-restore-alt"></i></span>
              </a>
          </div>
          <div class="col-auto my-custom">
              <a href="{{ route('holiday.calender') }}" class="action-btn" data-toggle="tooltip" data-original-title="{{__('Calender View')}}">
                  <i class="fa fa-calendar"></i>
              </a>
          </div>
            <div class="col-auto my-custom">
                <a href="#" data-url="{{ route('holiday.create') }}" class="btn btn-xs btn-white btn-icon-only width-auto" data-ajax-popup="true" data-title="{{__('Create New Holiday')}}">
                    <i class="fa fa-plus"></i> {{__('Create')}}
                </a>
            </div>

            {{ Form::close() }}
        </div>
    @endcan
@endsection

@section('content')
    <div class="mt-1">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Occasion')}}</th>
                                @if(Gate::check('edit holiday') || Gate::check('delete holiday'))
                                    <th>{{__('Action')}}</th>
                                @endif
                            </tr>
                            </thead>
                            <tbody class="font-style">
                            @foreach ($holidays as $holiday)
                                <tr>
                                    <td>{{ \Auth::user()->dateFormat($holiday->date) }}</td>
                                    <td>{{ $holiday->occasion }}</td>
                                    @if(Gate::check('edit holiday') || Gate::check('delete holiday'))
                                        <td class="Action">
                                            <span>
                                            @can('edit holiday')
                                                    <a href="#" class="edit-icon" data-url="{{ route('holiday.edit',$holiday->id) }}" data-ajax-popup="true" data-title="{{__('Edit Holiday')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                @endcan
                                                @can('delete holiday')
                                                    <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$holiday->id}}').submit();">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['holiday.destroy', $holiday->id],'id'=>'delete-form-'.$holiday->id]) !!}
                                                    {!! Form::close() !!}
                                                @endcan
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
    </div>

@endsection
