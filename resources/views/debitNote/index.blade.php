@extends('layouts.admin')
@section('page-title')
    {{__('Manage Debit Notes')}}
@endsection
@push('script-page')
    <script>
        $(document).on('change', '#bill', function () {

            var id = $(this).val();
            var url = "{{route('bill.get')}}";

            $.ajax({
                url: url,
                type: 'get',
                cache: false,
                data: {
                    'bill_id': id,

                },
                success: function (data) {
                    $('#amount').val(data)
                },

            });

        })
    </script>
@endpush

@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
        @can('create debit note')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-url="{{ route('bill.custom.debit.note') }}" data-ajax-popup="true" data-title="{{__('Create New Debit Note')}}" class="btn btn-xs btn-white btn-icon-only width-auto">
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
                                <th> {{__('Bill')}}</th>
                                <th> {{__('Vendor')}}</th>
                                <th> {{__('Date')}}</th>
                                <th> {{__('Amount')}}</th>
                                <th> {{__('Description')}}</th>
                                <th> {{__('Action')}}</th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach ($bills as $bill)
                                @if(!empty($bill->debitNote))
                                    @foreach ($bill->debitNote as $debitNote)
                                        <tr class="font-style">
                                            <td class="Id">
                                                <a href="{{ route('bill.show',\Crypt::encrypt($debitNote->bill) ) }}">{{ AUth::user()->billNumberFormat($bill->bill_id) }}
                                                </a>
                                            </td>
                                            <td>{{ (!empty($bill->vender)?$bill->vender->name:'-') }}</td>
                                            <td>{{ Auth::user()->dateFormat($debitNote->date) }}</td>
                                            <td>{{ Auth::user()->priceFormat($debitNote->amount) }}</td>
                                            <td>{{!empty($debitNote->description)?$debitNote->description:'-'}}</td>
                                            <td class="Action">
                                                <span>
                                                @can('edit debit note')
                                                        <a data-url="{{ route('bill.edit.debit.note',[$debitNote->bill,$debitNote->id]) }}" data-ajax-popup="true" data-title="{{__('Edit Debit Note')}}" href="#" class="edit-icon" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                        <i class="fas fa-pencil-alt"></i>
                                                    </a>
                                                    @endcan
                                                    @can('edit debit note')
                                                        <a href="#" class="delete-icon " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$debitNote->id}}').submit();">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                        {!! Form::open(['method' => 'DELETE', 'route' => array('bill.delete.debit.note', $debitNote->bill,$debitNote->id),'id'=>'delete-form-'.$debitNote->id]) !!}
                                                        {!! Form::close() !!}
                                                    @endcan
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
