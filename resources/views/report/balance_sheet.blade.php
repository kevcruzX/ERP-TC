@extends('layouts.admin')
@section('page-title')
    {{__('Balance Sheet')}}
@endsection
@push('script-page')
    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script>
        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {type: 'jpeg', quality: 1},
                html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                jsPDF: {unit: 'in', format: 'A2'}
            };
            html2pdf().set(opt).from(element).save();
        }

    </script>
@endpush

@section('action-button')
    <div class="row d-flex justify-content-end">
        <div class="col-auto">
            {{ Form::open(array('route' => array('report.balance.sheet'),'method' => 'GET','id'=>'report_bill_summary')) }}
            <div class="all-select-box">
                <div class="btn-box">
                    {{ Form::label('start_date', __('Start Date'),['class'=>'text-type']) }}
                    {{ Form::date('start_date',$filter['startDateRange'], array('class' => 'month-btn form-control')) }}
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="all-select-box">
                <div class="btn-box">
                    {{ Form::label('end_date', __('End Date'),['class'=>'text-type']) }}
                    {{ Form::date('end_date',$filter['endDateRange'], array('class' => 'month-btn form-control')) }}
                </div>
            </div>
        </div>
        <div class="col-auto my-custom">
            <a href="#" class="apply-btn" onclick="document.getElementById('report_bill_summary').submit(); return false;" data-toggle="tooltip" data-original-title="{{__('apply')}}">
                <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
            </a>
            <a href="{{route('report.balance.sheet')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
                <span class="btn-inner--icon"><i class="fas fa-trash-restore-alt"></i></span>
            </a>
            <a href="#" class="action-btn" onclick="saveAsPDF()" data-toggle="tooltip" data-original-title="{{__('Download')}}">
                <span class="btn-inner--icon"><i class="fas fa-download"></i></span>
            </a>
        </div>
    </div>
    {{ Form::close() }}

@endsection

@section('content')
    <div id="printableArea">
        <div class="row mt-4">
            <div class="col">
                <input type="hidden" value="{{__('Balance Sheet').' '.'Report of'.' '.$filter['startDateRange'].' to '.$filter['endDateRange']}}" id="filename">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Report')}} :</h5>
                    <h5 class="report-text mb-0">{{__('Balance Sheet')}}</h5>
                </div>
            </div>

            <div class="col">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Duration')}} :</h5>
                    <h5 class="report-text mb-0">{{$filter['startDateRange'].' to '.$filter['endDateRange']}}</h5>
                </div>
            </div>
        </div>

        <div class="row">
            @foreach($chartAccounts as $type => $accounts)
                @php $totalNetAmount=0; @endphp

                @foreach($accounts as  $accountData)
                    @foreach($accountData['account'] as  $account)
                        @php $totalNetAmount+=$account['netAmount']; @endphp
                    @endforeach
                @endforeach
                <div class="col">
                    <div class="card p-4 mb-4">
                        <h5 class="report-text gray-text mb-0">{{__('Total'.' '.$type)}}</h5>
                        <h5 class="report-text mb-0">
                            @if($totalNetAmount<0)
                                {{__('Dr').'. '.\Auth::user()->priceFormat(abs($totalNetAmount))}}
                            @elseif($totalNetAmount>0)
                                {{__('Cr').'. '.\Auth::user()->priceFormat($totalNetAmount)}}
                            @else
                                {{\Auth::user()->priceFormat(0)}}
                            @endif
                        </h5>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="row mb-4">
            @foreach($chartAccounts as $type => $accounts)
                <div class="col-lg-12 mb-4">
                    <h5 class="text-muted">{{$type}}</h5>
                    <div class="row">
                        @foreach($accounts as $account)

                            <div class="col-lg-4 col-md-4 mb-4">
                                <div class="card card-fluid">
                                    <table class="table table-flush">
                                        <thead>
                                        <tr>
                                            <th colspan="2" width="80%"><h6> {{$account['subType']}}</h6></th>
                                        </tr>
                                        <tr>
                                            <th width="80%"> {{__('Account')}}</th>
                                            <th> {{__('Amount')}}</th>
                                        </tr>
                                        </thead>
                                        <tbody class="balance-sheet-body">
                                        @php $totalCredit=0;$totalDebit=0;@endphp
                                        @foreach($account['account'] as  $record)
                                            @php
                                                $totalCredit+=$record['totalCredit'];
                                                $totalDebit+=$record['totalDebit'];
                                            @endphp
                                            <tr>
                                                <td>{{$record['account_name']}}</td>
                                                <td>
                                                    @if($record['netAmount']<0)
                                                        {{__('Dr').'. '.\Auth::user()->priceFormat(abs($record['netAmount']))}}
                                                    @elseif($record['netAmount']>0)
                                                        {{__('Cr').'. '.\Auth::user()->priceFormat($record['netAmount'])}}
                                                    @else
                                                        {{\Auth::user()->priceFormat(0)}}
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach

                                        </tbody>
                                        <thead>
                                        <tr>
                                            <th>{{__('Total').' '.$account['subType']}}</th>
                                            <th>
                                                @php $total= $totalCredit-$totalDebit; @endphp
                                                @if($total<0)
                                                    {{__('Dr').'. '.\Auth::user()->priceFormat(abs($total))}}
                                                @elseif($total>0)
                                                    {{__('Cr').'. '.\Auth::user()->priceFormat($total)}}
                                                @else
                                                    {{\Auth::user()->priceFormat(0)}}
                                                @endif
                                            </th>
                                        </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
