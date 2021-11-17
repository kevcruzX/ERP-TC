@extends('layouts.admin')

@section('page-title')
    {{__('Orders')}}
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped dataTable" width="100%">
                            <thead>
                            <tr>
                                <th>{{__('Order Id')}}</th>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Plan Name')}}</th>
                                <th>{{__('Price')}}</th>
                                <th>{{__('Status')}}</th>
                                <th>{{__('Payment Type')}}</th>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Coupon')}}</th>
                                <th>{{__('Invoice')}}</th>
                            </tr>
                            </thead>

                            <tbody>
                            @foreach($orders as $order)
                                <tr>
                                    <td>{{$order->order_id}}</td>
                                    <td>{{$order->user_name}}</td>
                                    <td>{{$order->plan_name}}</td>
                                    <td>$ {{number_format($order->price)}}</td>
                                    <td>
                                        @if($order->payment_status == 'succeeded')
                                            <span class="badge badge-pill badge-success">{{ucfirst($order->payment_status)}}</span>
                                        @else
                                            <span class="badge badge-pill badge-danger">{{ucfirst($order->payment_status)}}</span>
                                        @endif
                                    </td>
                                    <td>{{$order->payment_type}}</td>
                                    <td>{{$order->created_at->format('d M Y')}}</td>
                                    <td>{{!empty($order->use_coupon)?$order->use_coupon->coupon_detail->name:'-'}}</td>
                                    <td class="Id">
                                        @if(empty($order->receipt))
                                            <p>{{__('Manually plan upgraded by Super Admin')}}</p>
                                        @elseif($order->receipt =='free coupon')
                                            <p>{{__('Used 100 % discount coupon code.')}}</p>
                                        @else
                                            <a href="{{$order->receipt}}" target="_blank"><i class="fas fa-file-invoice"></i> {{__('Invoice')}}</a>
                                        @endif
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
