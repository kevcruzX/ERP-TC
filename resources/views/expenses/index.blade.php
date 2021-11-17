@extends('layouts.admin')

@section('page-title')
    {{ucwords($project->project_name).__("'s Expenses")}}
@endsection

@section('action-button')
    <div class="col-md-6 d-flex align-items-center justify-content-between justify-content-md-end">
        @can('create expense')
            <a href="#" class="btn btn-xs btn-white btn-icon-only width-auto ml-2" data-url="{{ route('projects.expenses.create',$project->id) }}" data-ajax-popup="true" data-size="lg" data-title="{{__('Create Expense')}}">
                <span class="btn-inner--icon"><i class="fas fa-plus"></i>{{__('Create')}}</span>
            </a>
        @endcan
        <a href="{{ route('projects.show',$project->id) }}" class="btn btn-xs btn-white btn-icon-only width-auto">
            <span class="btn-inner--icon"><i class="fas fa-arrow-left"></i>{{__('Back')}}</span>
        </a>
    </div>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="table-responsive">
                    <table class="table align-items-center">
                        <thead>
                        <tr>
                            <th scope="col">{{__('Attachment')}}</th>
                            <th scope="col">{{__('Name')}}</th>
                            <th scope="col">{{__('Date')}}</th>
                            <th scope="col">{{__('Amount')}}</th>
                            @if(Gate::check('edit expense') || Gate::check('delete expense'))
                                <th scope="col"></th>
                            @endif
                        </tr>
                        </thead>
                        <tbody class="list">
                            @if(isset($project->expense) && !empty($project->expense) && count($project->expense) > 0)
                                @foreach($project->expense as $expense)
                                    <tr>
                                        <th scope="row">
                                            @if(!empty($expense->attachment))
                                                <a href="{{ asset(Storage::url($expense->attachment)) }}" class="btn btn-sm btn-secondary btn-icon rounded-pill" download>
                                                    <span class="btn-inner--icon"><i class="fas fa-download"></i></span>
                                                </a>
                                            @else
                                                <a href="#" class="btn btn-sm btn-secondary btn-icon rounded-pill">
                                                    <span class="btn-inner--icon"><i class="fas fa-times-circle"></i></span>
                                                </a>
                                            @endif
                                        </th>
                                        <td>
                                            <span class="h6 text-sm font-weight-bold mb-0">{{ $expense->name }}</span>
                                            @if(!empty($expense->task))<span class="d-block text-sm text-muted">{{ $expense->task->name }}</span>@endif
                                        </td>
                                        <td>{{ (!empty($expense->date)) ? \App\Utility::getDateFormated($expense->date) : '-' }}</td>
                                        <td>{{ \Auth::user()->priceFormat($expense->amount) }}</td>
                                        @if(Gate::check('edit expense') || Gate::check('delete expense'))
                                            <td class="text-right w-15">
                                                <div class="actions">
                                                    @can('edit expense')
                                                        <a href="#" class="action-item px-2" data-url="{{ route('projects.expenses.edit',[$project->id,$expense->id]) }}" data-ajax-popup="true" data-size="lg" data-title="{{__('Edit ').$expense->name}}" data-toggle="tooltip" data-original-title="Edit">
                                                            <span class="btn-inner--icon"><i class="fas fa-pencil-alt"></i></span>
                                                        </a>
                                                    @endcan
                                                    @can('delete expense')
                                                        <a href="#" class="action-item text-danger px-2" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?')}}|{{__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-expense-{{$expense->id}}').submit();">
                                                            <i class="fas fa-trash-alt"></i>
                                                        </a>
                                                    @endcan
                                                </div>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['projects.expenses.destroy',$expense->id],'id'=>'delete-expense-'.$expense->id]) !!}
                                                {!! Form::close() !!}
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <th scope="col" colspan="5"><h6 class="text-center">{{__('No Expense Found.')}}</h6></th>
                                </tr>
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
