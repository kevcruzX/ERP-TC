@extends('layouts.admin')
@section('page-title')
    {{__('Proposal Detail')}}
@endsection
@push('script-page')
    <script>
        $(document).on('change', '.status_change', function () {
            var status = this.value;
            var url = $(this).data('url');
            $.ajax({
                url: url + '?status=' + status,
                type: 'GET',
                cache: false,
                success: function (data) {
                },
            });
        });
    </script>
@endpush
@section('content')
    @can('send proposal')
        @if($proposal->status!=4)
            <div class="row">
                <div class="col-12">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                                <div class="timeline-block">
                                    <span class="timeline-step timeline-step-sm bg-primary border-primary text-white"><i class="fas fa-plus"></i></span>
                                    <div class="timeline-content">
                                        <div class="text-sm h6">{{__('Create Proposal')}}</div>
                                        @can('edit proposal')
                                            <div class="Action">
                                                <a href="{{ route('proposal.edit',\Crypt::encrypt($proposal->id)) }}" class="edit-icon float-right" data-toggle="tooltip" data-original-title="{{__('Edit')}}"><i class="fas fa-pencil-alt"></i></a>
                                            </div>
                                        @endcan
                                        <small><i class="fas fa-clock mr-1"></i>{{__('Created on ')}} {{\Auth::user()->dateFormat($proposal->issue_date)}}</small>
                                    </div>
                                </div>
                                <div class="timeline-block">
                                    <span class="timeline-step timeline-step-sm bg-warning border-warning text-white"><i class="fas fa-envelope"></i></span>
                                    <div class="timeline-content">
                                        <div class="text-sm h6 ">{{__('Send Proposal')}}</div>
                                        @if($proposal->status==0)
                                            <div class="Action">
                                                @can('send proposal')
                                                    <a href="{{ route('proposal.sent',$proposal->id) }}" class="send-icon float-right" data-toggle="tooltip" data-original-title="{{__('Mark Sent')}}"><i class="fa fa-paper-plane"></i></a>
                                                @endcan
                                            </div>
                                        @endif
                                        @if($proposal->status!=0)
                                            <small>{{__('Sent on')}} {{\Auth::user()->dateFormat($proposal->send_date)}}</small>
                                        @else
                                            <small>{{__('Status')}} : {{__('Not Sent')}}</small>
                                        @endif
                                    </div>
                                </div>
                                <div class="timeline-block">
                                    <span class="timeline-step timeline-step-sm bg-info border-info text-white"><i class="far fa-money-bill-alt"></i></span>
                                    <div class="timeline-content">
                                        <span class="text-sm h6 ">{{__('Proposal Status')}}</span>
                                        <div class="float-right" data-toggle="tooltip" data-original-title="{{__('Click to change status')}}">
                                            <select class="form-control status_change select2" name="status" data-url="{{route('proposal.status.change',$proposal->id)}}">
                                                @foreach($status as $k=>$val)
                                                    <option value="{{$k}}" {{($proposal->status==$k)?'selected':''}}>{{$val}}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <br>
                                        <small>
                                            @if($proposal->status == 0)
                                                <span class="badge badge-pill badge-primary">{{ __(\App\Proposal::$statues[$proposal->status]) }}</span>
                                            @elseif($proposal->status == 1)
                                                <span class="badge badge-pill badge-info">{{ __(\App\Proposal::$statues[$proposal->status]) }}</span>
                                            @elseif($proposal->status == 2)
                                                <span class="badge badge-pill badge-success">{{ __(\App\Proposal::$statues[$proposal->status]) }}</span>
                                            @elseif($proposal->status == 3)
                                                <span class="badge badge-pill badge-warning">{{ __(\App\Proposal::$statues[$proposal->status]) }}</span>
                                            @elseif($proposal->status == 4)
                                                <span class="badge badge-pill badge-danger">{{ __(\App\Proposal::$statues[$proposal->status]) }}</span>
                                            @endif
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    @endcan

    @if(\Auth::user()->type=='company')
        @if($proposal->status!=0)
            <div class="row justify-content-between align-items-center mb-3">
                <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                    <div class="all-button-box mx-2">
                        <a href="{{ route('proposal.resent',$proposal->id) }}" class="btn btn-xs btn-white btn-icon-only width-auto">{{__('Resend Proposal')}}</a>
                    </div>
                    <div class="all-button-box">
                        <a href="{{ route('proposal.pdf', Crypt::encrypt($proposal->id))}}" class="btn btn-xs btn-white btn-icon-only width-auto" target="_blank">{{__('Download')}}</a>
                    </div>
                </div>
            </div>
        @endif
    @else
        <div class="row justify-content-between align-items-center mb-3">
            <div class="col-md-12 d-flex align-items-center justify-content-between justify-content-md-end">
                <div class="all-button-box">
                    <a href="{{ route('proposal.pdf', Crypt::encrypt($proposal->id))}}" class="btn btn-xs btn-white btn-icon-only width-auto" target="_blank">{{__('Download')}}</a>
                </div>
            </div>
        </div>
    @endif

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="invoice">
                        <div class="invoice-print">
                            <div class="row invoice-title mt-2">
                                <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12">
                                    <h2>{{__('Proposal')}}</h2>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-nd-6 col-lg-6 col-12 text-right">
                                    <h3 class="invoice-number">{{ Auth::user()->proposalNumberFormat($proposal->proposal_id) }}</h3>
                                </div>
                                <div class="col-12">
                                    <hr>
                                </div>
                            </div>
                            <div class="row">
                                @if(!empty($customer->billing_name))
                                    <div class="col-md-6">
                                        <small class="font-style">
                                            <strong>{{__('Billed To')}} :</strong><br>
                                            {{!empty($customer->billing_name)?$customer->billing_name:''}}<br>
                                            {{!empty($customer->billing_phone)?$customer->billing_phone:''}}<br>
                                            {{!empty($customer->billing_address)?$customer->billing_address:''}}<br>
                                            {{!empty($customer->billing_zip)?$customer->billing_zip:''}}<br>
                                            {{!empty($customer->billing_city)?$customer->billing_city:'' .', '}} {{!empty($customer->billing_state)?$customer->billing_state:'',', '}} {{!empty($customer->billing_country)?$customer->billing_country:''}}
                                        </small>
                                    </div>
                                @endif

                                @if(\Utility::getValByName('shipping_display')=='on')
                                    <div class="col-md-6 text-md-right">
                                        <small>
                                            <strong>{{__('Shipped To')}} :</strong><br>
                                            {{!empty($customer->shipping_name)?$customer->shipping_name:''}}<br>
                                            {{!empty($customer->shipping_phone)?$customer->shipping_phone:''}}<br>
                                            {{!empty($customer->shipping_address)?$customer->shipping_address:''}}<br>
                                            {{!empty($customer->shipping_zip)?$customer->shipping_zip:''}}<br>
                                            {{!empty($customer->shipping_city)?$customer->shipping_city:'' . ', '}} {{!empty($customer->shipping_state)?$customer->shipping_state:'' .', '}},{{!empty($customer->shipping_country)?$customer->shipping_country:''}}
                                        </small>
                                    </div>
                                @endif
                            </div>
                            <div class="row mt-3">
                                <div class="col">
                                    <small>
                                        <strong>{{__('Status')}} :</strong><br>
                                        @if($proposal->status == 0)
                                            <span class="badge badge-pill badge-primary">{{ __(\App\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 1)
                                            <span class="badge badge-pill badge-info">{{ __(\App\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 2)
                                            <span class="badge badge-pill badge-success">{{ __(\App\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 3)
                                            <span class="badge badge-pill badge-warning">{{ __(\App\Proposal::$statues[$proposal->status]) }}</span>
                                        @elseif($proposal->status == 4)
                                            <span class="badge badge-pill badge-danger">{{ __(\App\Proposal::$statues[$proposal->status]) }}</span>
                                        @endif
                                    </small>
                                </div>
                                <div class="col text-md-right">
                                    <small>
                                        <strong>{{__('Issue Date')}} :</strong><br>
                                        {{\Auth::user()->dateFormat($proposal->issue_date)}}<br><br>
                                    </small>
                                </div>

                                @if(!empty($customFields) && count($proposal->customField)>0)
                                    @foreach($customFields as $field)
                                        <div class="col text-md-right">
                                            <small>
                                                <strong>{{$field->name}} :</strong><br>
                                                {{!empty($proposal->customField)?$proposal->customField[$field->id]:'-'}}
                                                <br><br>
                                            </small>
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <div class="font-weight-bold">{{__('Product Summary')}}</div>
                                    <small>{{__('All items here cannot be deleted.')}}</small>
                                    <div class="table-responsive mt-2">
                                        <table class="table mb-0 table-striped">
                                            <tr>
                                                <th class="text-dark" data-width="40">#</th>
                                                <th class="text-dark">{{__('Product')}}</th>
                                                <th class="text-dark">{{__('Quantity')}}</th>
                                                <th class="text-dark">{{__('Rate')}}</th>
                                                <th class="text-dark">{{__('Tax')}}</th>
                                                <th class="text-dark"> @if($proposal->discount_apply==1){{__('Discount')}}@endif</th>
                                                <th class="text-dark">{{__('Description')}}</th>
                                                <th class="text-right text-dark" width="12%">{{__('Price')}}<br>
                                                    <small class="text-danger font-weight-bold">{{__('before tax & discount')}}</small>
                                                </th>
                                            </tr>
                                            @php
                                                $totalQuantity=0;
                                                $totalRate=0;
                                                $totalTaxPrice=0;
                                                $totalDiscount=0;
                                                $taxesData=[];
                                            @endphp

                                            @foreach($iteams as $key =>$iteam)
                                                @if(!empty($iteam->tax))
                                                    @php
                                                        $taxes=\Utility::tax($iteam->tax);
                                                        $totalQuantity+=$iteam->quantity;
                                                        $totalRate+=$iteam->price;
                                                        $totalDiscount+=$iteam->discount;
                                                        foreach($taxes as $taxe){
                                                            $taxDataPrice=\Utility::taxRate($taxe->rate,$iteam->price,$iteam->quantity);
                                                            if (array_key_exists($taxe->name,$taxesData))
                                                            {
                                                                $taxesData[$taxe->name] = $taxesData[$taxe->name]+$taxDataPrice;
                                                            }
                                                            else
                                                            {
                                                                $taxesData[$taxe->name] = $taxDataPrice;
                                                            }
                                                        }
                                                    @endphp
                                                @endif
                                                <tr>
                                                    <td>{{$key+1}}</td>
                                                    <td>{{!empty($iteam->product)?$iteam->product->name:''}}</td>
                                                    <td>{{$iteam->quantity}}</td>
                                                    <td>{{\Auth::user()->priceFormat($iteam->price)}}</td>
                                                    <td>
                                                        @if(!empty($iteam->tax))
                                                            <table>
                                                                @php $totalTaxRate = 0;@endphp
                                                                @foreach($taxes as $tax)
                                                                    @php
                                                                        $taxPrice=\Utility::taxRate($tax->rate,$iteam->price,$iteam->quantity);
                                                                        $totalTaxPrice+=$taxPrice;
                                                                    @endphp
                                                                    <tr>
                                                                        <td>{{$tax->name .' ('.$tax->rate .'%)'}}</td>
                                                                        <td>{{\Auth::user()->priceFormat($taxPrice)}}</td>
                                                                    </tr>
                                                                @endforeach
                                                            </table>
                                                        @else
                                                            -
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($proposal->discount_apply==1)
                                                            {{\Auth::user()->priceFormat($iteam->discount)}}
                                                        @endif
                                                    </td>
                                                    <td>{{!empty($iteam->description)?$iteam->description:'-'}}</td>
                                                    <td class="text-right">{{\Auth::user()->priceFormat(($iteam->price*$iteam->quantity))}}</td>
                                                </tr>
                                            @endforeach
                                            <tfoot>
                                            <tr>
                                                <td></td>
                                                <td><b>{{__('Total')}}</b></td>
                                                <td><b>{{$totalQuantity}}</b></td>
                                                <td><b>{{\Auth::user()->priceFormat($totalRate)}}</b></td>
                                                <td><b>{{\Auth::user()->priceFormat($totalTaxPrice)}}</b></td>
                                                <td>
                                                    @if($proposal->discount_apply==1)
                                                        <b>{{\Auth::user()->priceFormat($totalDiscount)}}</b>
                                                    @endif
                                                </td>
                                                <td></td>
                                                <td></td>
                                            </tr>
                                            <tr>
                                                <td colspan="6"></td>
                                                <td class="text-right"><b>{{__('Sub Total')}}</b></td>
                                                <td class="text-right">{{\Auth::user()->priceFormat($proposal->getSubTotal())}}</td>
                                            </tr>
                                            @if($proposal->discount_apply==1)
                                                <tr>
                                                    <td colspan="6"></td>
                                                    <td class="text-right"><b>{{__('Discount')}}</b></td>
                                                    <td class="text-right">{{\Auth::user()->priceFormat($proposal->getTotalDiscount())}}</td>
                                                </tr>
                                            @endif
                                            @if(!empty($taxesData))
                                                @foreach($taxesData as $taxName => $taxPrice)
                                                    <tr>
                                                        <td colspan="6"></td>
                                                        <td class="text-right"><b>{{$taxName}}</b></td>
                                                        <td class="text-right">{{ \Auth::user()->priceFormat($taxPrice) }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                            <tr>
                                                <td colspan="6"></td>
                                                <td class="blue-text text-right"><b>{{__('Total')}}</b></td>
                                                <td class="blue-text text-right">{{\Auth::user()->priceFormat($proposal->getTotal())}}</td>
                                            </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
