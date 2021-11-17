@extends('layouts.admin')
@php
    $profile=asset(Storage::url('uploads/avatar/'));
@endphp
@push('script-page')
    <script>
        $(document).on('click', '#billing_data', function () {
            $("[name='shipping_name']").val($("[name='billing_name']").val());
            $("[name='shipping_country']").val($("[name='billing_country']").val());
            $("[name='shipping_state']").val($("[name='billing_state']").val());
            $("[name='shipping_city']").val($("[name='billing_city']").val());
            $("[name='shipping_phone']").val($("[name='billing_phone']").val());
            $("[name='shipping_zip']").val($("[name='billing_zip']").val());
            $("[name='shipping_address']").val($("[name='billing_address']").val());
        })

    </script>
@endpush
@section('page-title')
    {{__('Manage Customers')}}
@endsection

@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
        @can('create customer')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-size="2xl" data-url="{{ route('customer.create') }}" data-ajax-popup="true" data-title="{{__('Create New Customer')}}" class="btn btn-xs btn-white btn-icon-only width-auto commonModal">
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
                                <th>#</th>
                                <th> {{__('Name')}}</th>
                                <th> {{__('Contact')}}</th>
                                <th> {{__('Email')}}</th>
                                <th> {{__('Balance')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($customers as $k=>$customer)
                                <tr class="cust_tr" id="cust_detail" data-url="{{route('customer.show',\Crypt::encrypt($customer['id']))}}" data-id="{{$customer['id']}}">
                                    <td class="Id">
                                        @can('show customer')
                                            <a href="{{ route('customer.show',\Crypt::encrypt($customer['id'])) }}">
                                                {{ AUth::user()->customerNumberFormat($customer['customer_id']) }}
                                            </a>
                                        @else
                                            <a href="#" class="btn btn-outline-primary">
                                                {{ AUth::user()->customerNumberFormat($customer['customer_id']) }}
                                            </a>
                                        @endcan
                                    </td>
                                    <td class="font-style">{{$customer['name']}}</td>
                                    <td>{{$customer['contact']}}</td>
                                    <td>{{$customer['email']}}</td>
                                    <td>{{\Auth::user()->priceFormat($customer['balance'])}}</td>
                                    <td class="Action">
                                        <span>
                                        @if($customer['is_active']==0)
                                                <i class="fa fa-lock" title="Inactive"></i>
                                            @else
                                                @can('show customer')
                                                    <a href="{{ route('customer.show',\Crypt::encrypt($customer['id'])) }}" class="edit-icon bg-success" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @endcan
                                                @can('edit customer')
                                                    <a href="#" class="edit-icon" data-size="2xl" data-url="{{ route('customer.edit',$customer['id']) }}" data-ajax-popup="true" data-title="{{__('Edit Customer')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                @endcan
                                                @can('delete customer')
                                                    <a href="#" class="delete-icon " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{ $customer['id']}}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['customer.destroy', $customer['id']],'id'=>'delete-form-'.$customer['id']]) !!}
                                                    {!! Form::close() !!}
                                                @endcan
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
    </div>
@endsection
