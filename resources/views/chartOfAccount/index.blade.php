@extends('layouts.admin')
@section('page-title')
    {{__('Manage Chart of Accounts')}}
@endsection
@push('script-page')
    <script>
        $(document).on('change', '#type', function () {
            var type = $(this).val();
            $.ajax({
                url: '{{route('charofAccount.subType')}}',
                type: 'POST',
                data: {
                    "type": type, "_token": "{{ csrf_token() }}",
                },
                success: function (data) {
                    $('#sub_type').empty();
                    $.each(data, function (key, value) {
                        $('#sub_type').append('<option value="' + key + '">' + value + '</option>');
                    });
                }
            });
        });

    </script>
@endpush
@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
        @can('create chart of account')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-url="{{ route('chart-of-account.create') }}" data-ajax-popup="true" data-title="{{__('Create New Account')}}" class="btn btn-xs btn-white btn-icon-only width-auto">
                    <i class="fas fa-plus"></i> {{__('Create')}}
                </a>
            </div>
        @endcan
    </div>
@endsection
@section('content')


    <div class="row">
        @foreach($chartAccounts as $type=>$accounts)
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h6>{{$type}}</h6>
                    </div>
                    <div class="card-body py-0">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th> {{__('Code')}}</th>
                                    <th> {{__('Name')}}</th>
                                    <th> {{__('Type')}}</th>
                                    <th> {{__('Balance')}}</th>
                                    <th> {{__('Status')}}</th>
                                    <th> {{__('Action')}}</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($accounts as $account)

                                    <tr>
                                        <td>{{ $account->code }}</td>
                                        <td><a href="{{route('report.ledger')}}?account={{$account->id}}">{{ $account->name }}</a></td>
                                        <td>{{!empty($account->subType)?$account->subType->name:'-'}}</td>
                                        <td>
                                            @if(!empty($account->balance()) && $account->balance()['netAmount']<0)
                                                {{__('Dr').'. '.\Auth::user()->priceFormat(abs($account->balance()['netAmount']))}}
                                            @elseif(!empty($account->balance()) && $account->balance()['netAmount']>0)
                                                {{__('Cr').'. '.\Auth::user()->priceFormat($account->balance()['netAmount'])}}
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if($account->is_enabled==1)
                                                <span class="badge badge-success">{{__('Enabled')}}</span>
                                            @else
                                                <span class="badge badge-danger">{{__('Disabled')}}</span>
                                            @endif
                                        </td>
                                        <td class="Action">
                                            <a href="{{route('report.ledger')}}?account={{$account->id}}" class="edit-icon bg-info" data-toggle="tooltip" data-original-title="{{__('Ledger Summary')}}">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @can('edit chart of account')
                                                <a href="#" class="edit-icon" data-url="{{ route('chart-of-account.edit',$account->id) }}" data-ajax-popup="true" data-title="{{__('Edit Unit')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                            @endcan
                                            @can('delete chart of account')
                                                <a href="#" class="delete-icon" data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{$account->id}}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                {!! Form::open(['method' => 'DELETE', 'route' => ['chart-of-account.destroy', $account->id],'id'=>'delete-form-'.$account->id]) !!}
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
        @endforeach
    </div>

@endsection
