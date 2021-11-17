@extends('layouts.admin')
@section('page-title')
    {{__('Trial Balance')}}
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
            {{ Form::open(array('route' => array('trial.balance'),'method' => 'GET','id'=>'report_trial_balance')) }}
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
            <a href="#" class="apply-btn" onclick="document.getElementById('report_trial_balance').submit(); return false;" data-toggle="tooltip" data-original-title="{{__('apply')}}">
                <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
            </a>
            <a href="{{route('trial.balance')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
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
                <input type="hidden" value="{{__('Trial Balance').' '.'Report of'.' '.$filter['startDateRange'].' to '.$filter['endDateRange']}}" id="filename">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Report')}} :</h5>
                    <h5 class="report-text mb-0">{{__('Trial Balance Summary')}}</h5>
                </div>
            </div>

            <div class="col">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Duration')}} :</h5>
                    <h5 class="report-text mb-0">{{$filter['startDateRange'].' to '.$filter['endDateRange']}}</h5>
                </div>
            </div>
        </div>
        @if(!empty($account))
            <div class="row mt-4">
                <div class="col">
                    <div class="card p-4 mb-4">
                        <h5 class="report-text gray-text mb-0">{{__('Total Credit')}} :</h5>
                        <h5 class="report-text mb-0">0</h5>
                    </div>
                </div>
                <div class="col">
                    <div class="card p-4 mb-4">
                        <h5 class="report-text gray-text mb-0">{{__('Total Debit')}} :</h5>
                        <h5 class="report-text mb-0">0</h5>
                    </div>
                </div>
            </div>
        @endif
        <div class="row mb-4">
            <div class="col-12 mb-4">
                <table class="table table-flush">
                    <thead>
                    <tr>
                        <th> {{__('Account Name')}}</th>
                        <th> {{__('Debit Total')}}</th>
                        <th> {{__('Credit Total')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php  $debitTotal=0;$creditTotal=0;@endphp
                    @foreach($journalItem as  $item)

                        <tr>
                            <td>{{$item['name']}}</td>
                            <td>
                                @if($item['netAmount']<0)
                                    @php
                                        $debitTotal+=abs($item['netAmount']);
                                    @endphp
                                    {{\Auth::user()->priceFormat(abs($item['netAmount']))}}
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($item['netAmount']>0)
                                    @php
                                        $creditTotal+=$item['netAmount'];
                                    @endphp
                                    {{\Auth::user()->priceFormat($item['netAmount'])}}
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfooter>
                        <td class="text-dark">{{__('Total')}}</td>
                        <td  class="text-dark">{{\Auth::user()->priceFormat($debitTotal)}}</td>
                        <td  class="text-dark">{{\Auth::user()->priceFormat($creditTotal)}}</td>
                    </tfooter>
                </table>
            </div>
        </div>
    </div>
@endsection
