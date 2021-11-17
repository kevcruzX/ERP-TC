@extends('layouts.admin')
@section('page-title')
    {{__('Manage Journal Entry')}}
@endsection

@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
        @can('create journal entry')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="{{ route('journal-entry.create') }}" data-title="{{__('Create New Journal')}}" class="btn btn-xs btn-white btn-icon-only width-auto">
                    <i class="fas fa-plus"></i> {{__('Create')}}
                </a>
            </div>
        @endcan
    </div>
@endsection
@section('content')

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body py-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0 dataTable">
                            <thead>
                            <tr>
                                <th> {{__('Journal ID')}}</th>
                                <th> {{__('Date')}}</th>
                                <th> {{__('Amount')}}</th>
                                <th> {{__('Description')}}</th>
                                <th> {{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($journalEntries as $journalEntry)
                                <tr>
                                    <td class="Id">
                                        <a href="{{ route('journal-entry.show',$journalEntry->id) }}">{{ AUth::user()->journalNumberFormat($journalEntry->journal_id) }}</a>
                                    </td>
                                    <td>{{ Auth::user()->dateFormat($journalEntry->date) }}</td>
                                    <td>
                                        {{ \Auth::user()->priceFormat($journalEntry->totalCredit())}}
                                    </td>
                                    <td>{{!empty($journalEntry->description)?$journalEntry->description:'-'}}</td>
                                    <td>
                                        @can('edit journal entry')
                                            <a data-title="{{__('Edit Journal')}}" href="{{ route('journal-entry.edit',[$journalEntry->id]) }}" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                        @endcan
                                        @can('delete journal entry')
                                            <a href="#" class="delete-icon " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$journalEntry->id}}').submit();">
                                                <i class="fas fa-trash"></i>
                                            </a>
                                            {!! Form::open(['method' => 'DELETE', 'route' => array('journal-entry.destroy', $journalEntry->id),'id'=>'delete-form-'.$journalEntry->id]) !!}
                                            {!! Form::close() !!}
                                        @endcan
                                    </td>
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
