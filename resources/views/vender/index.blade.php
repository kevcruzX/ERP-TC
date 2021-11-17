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
    {{__('Manage Vendors')}}
@endsection

@section('action-button')
    <div class="all-button-box row d-flex justify-content-end">
        @can('create vender')
            <div class="col-xl-2 col-lg-2 col-md-4 col-sm-6 col-6">
                <a href="#" data-size="2xl" data-url="{{ route('vender.create') }}" data-ajax-popup="true" data-title="{{__('Create New Vendor')}}" class="btn btn-xs btn-white btn-icon-only width-auto commonModal">
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
                                <th>{{__('Name')}}</th>
                                <th>{{__('Contact')}}</th>
                                <th>{{__('Email')}}</th>
                                <th>{{__('Balance')}}</th>
                                <th>{{__('Action')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach ($venders as $k=>$Vender)
                                <tr class="cust_tr" id="vend_detail">
                                    <td class="Id">
                                        @can('show vender')
                                            <a href="{{ route('vender.show',\Crypt::encrypt($Vender['id'])) }}"> {{ AUth::user()->venderNumberFormat($Vender['vender_id']) }}
                                            </a>
                                        @else
                                            <a href="#"> {{ AUth::user()->venderNumberFormat($Vender['vender_id']) }}
                                            </a>
                                        @endcan
                                    </td>
                                    <td>{{$Vender['name']}}</td>
                                    <td>{{$Vender['contact']}}</td>
                                    <td>{{$Vender['email']}}</td>
                                    <td>{{\Auth::user()->priceFormat($Vender['balance'])}}</td>
                                    <td class="Action">
                                        <span>
                                        @if($Vender['is_active']==0)
                                                <i class="fa fa-lock" title="Inactive"></i>
                                            @else
                                                @can('show vender')
                                                    <a href="{{ route('vender.show',\Crypt::encrypt($Vender['id'])) }}" class="edit-icon bg-info" data-toggle="tooltip" data-original-title="{{__('View')}}">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                @endcan
                                                @can('edit vender')
                                                    <a href="#" class="edit-icon" data-size="2xl" data-url="{{ route('vender.edit',$Vender['id']) }}" data-ajax-popup="true" data-title="{{__('Edit Vendor')}}" data-toggle="tooltip" data-original-title="{{__('Edit')}}">
                                                    <i class="fas fa-pencil-alt"></i>
                                                </a>
                                                @endcan
                                                @can('delete vender')
                                                    <a href="#" class="delete-icon " data-toggle="tooltip" data-original-title="{{__('Delete')}}" data-confirm="{{__('Are You Sure?').'|'.__('This action can not be undone. Do you want to continue?')}}" data-confirm-yes="document.getElementById('delete-form-{{ $Vender['id']}}').submit();">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                                    {!! Form::open(['method' => 'DELETE', 'route' => ['vender.destroy', $Vender['id']],'id'=>'delete-form-'.$Vender['id']]) !!}
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
