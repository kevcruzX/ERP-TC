@extends('layouts.admin')
@section('page-title')
    {{__('Account Statement Summary')}}
@endsection
@push('script-page')
    <script src="{{ asset('js/jspdf.min.js') }} "></script>
    <script type="text/javascript" src="{{ asset('js/html2pdf.bundle.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/jszip.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/pdfmake.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/vfs_fonts.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/dataTables.buttons.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/buttons.html5.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/js/buttons.print.min.js') }}"></script>
    <script>
        var filename = $('#filename').val();

        function saveAsPDF() {
            var element = document.getElementById('printableArea');
            var opt = {
                margin: 0.3,
                filename: filename,
                image: {type: 'jpeg', quality: 1},
                html2canvas: {scale: 4, dpi: 72, letterRendering: true},
                jsPDF: {unit: 'in', format: 'A4'}
            };
            html2pdf().set(opt).from(element).save();
        }

        $(document).ready(function () {
            var filename = $('#filename').val();
            $('#report-dataTable').DataTable({
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'excel',
                        title: filename
                    },
                    {
                        extend: 'pdf',
                        title: filename
                    },  {
                        extend: 'csv',
                        title: filename
                    }
                ]
            });
        });
    </script>
@endpush

@section('action-button')
    <div class="row d-flex justify-content-end">
        <div class="col-auto">
            {{ Form::open(array('route' => array('report.account.statement'),'method'=>'get','id'=>'report_account')) }}
            <div class="all-select-box">
                <div class="btn-box">
                    {{Form::label('start_month',__('Start Month'),['class'=>'text-type'])}}
                    {{Form::month('start_month',isset($_GET['start_month'])?$_GET['start_month']:date('Y-m'),array('class'=>'month-btn form-control'))}}
                </div>
            </div>
        </div>
        <div class="col-auto">
            <div class="all-select-box">
                <div class="btn-box">
                    {{Form::label('end_month',__('End Month'),['class'=>'text-type'])}}
                    {{Form::month('end_month',isset($_GET['end_month'])?$_GET['end_month']:date('Y-m', strtotime("-5 month")),array('class'=>'month-btn form-control'))}}
                </div>
            </div>
        </div>
        <div class="col">
            <div class="all-select-box">
                <div class="btn-box">
                    {{Form::label('account',__('Account'),['class'=>'text-type'])}}
                    {{Form::select('account', $account,isset($_GET['account'])?$_GET['account']:'', array('class' => 'form-control select2')) }}
                </div>
            </div>
        </div>
        <div class="col">
            <div class="all-select-box">
                <div class="btn-box">
                    {{ Form::label('type', __('Type'),['class'=>'text-type']) }}
                    {{ Form::select('type',$types,isset($_GET['type'])?$_GET['type']:'', array('class' => 'form-control select2')) }}
                </div>
            </div>
        </div>
        <div class="col-auto my-custom">
            <a href="#" class="apply-btn" onclick="document.getElementById('report_account').submit(); return false;" data-toggle="tooltip" data-original-title="{{__('apply')}}">
                <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
            </a>
            <a href="{{route('report.account.statement')}}" class="reset-btn" data-toggle="tooltip" data-original-title="{{__('Reset')}}">
                <span class="btn-inner--icon"><i class="fas fa-trash-restore-alt"></i></span>
            </a>
            <a href="#" class="action-btn" onclick="saveAsPDF()" data-toggle="tooltip" data-original-title="{{__('Download')}}">
                <span class="btn-inner--icon"><i class="fas fa-download"></i></span>
            </a>
        </div>
        {{ Form::close() }}
    </div>
@endsection

@section('content')
    <div id="printableArea">
        <div class="row mt-3">
            <div class="col">
                <input type="hidden" value="{{__('Account Statement').' '.$filter['type'].' '.'Report of'.' '.$filter['startDateRange'].' to '.$filter['endDateRange']}}" id="filename">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Report')}} :</h5>
                    <h5 class="report-text mb-0">{{__('Account Statement Summary')}}</h5>
                </div>
            </div>
            @if($filter['account']!=__('All'))
                <div class="col">
                    <div class="card p-4 mb-4">
                        <h5 class="report-text gray-text mb-0">{{__('Account')}} :</h5>
                        <h5 class="report-text mb-0">{{$filter['account']}}</h5>
                    </div>
                </div>
            @endif
            @if($filter['type']!=__('All'))
                <div class="col">
                    <div class="card p-4 mb-4">
                        <h5 class="report-text gray-text mb-0">{{__('Type')}} :</h5>
                        <h5 class="report-text mb-0">{{$filter['type']}}</h5>
                    </div>
                </div>
            @endif
            <div class="col">
                <div class="card p-4 mb-4">
                    <h5 class="report-text gray-text mb-0">{{__('Duration')}} :</h5>
                    <h5 class="report-text mb-0">{{$filter['startDateRange'].' to '.$filter['endDateRange']}}</h5>
                </div>
            </div>
        </div>

        @if(!empty($reportData['revenueAccounts']))
            <div class="row">
                @foreach($reportData['revenueAccounts'] as $account)
                    <div class="col-xl-3 col-md-6 col-lg-3">
                        <div class="card p-4 mb-4">
                            @if($account->holder_name =='Cash')
                                <h5 class="report-text gray-text mb-0">{{$account->holder_name}}</h5>
                            @elseif(empty($account->holder_name))
                                <h5 class="report-text gray-text mb-0">{{__('Stripe / Paypal')}}</h5>
                            @else
                                <h5 class="report-text gray-text mb-0">{{$account->holder_name.' - '.$account->bank_name}}</h5>
                            @endif
                            <h5 class="report-text mb-0">{{\Auth::user()->priceFormat($account->total)}}</h5>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if(!empty($reportData['paymentAccounts']))
            <div class="row">
                @foreach($reportData['paymentAccounts'] as $account)
                    <div class="col-xl-3 col-md-6 col-lg-3">
                        <div class="card p-4 mb-4">
                            @if($account->holder_name =='Cash')
                                <h5 class="report-text gray-text mb-0">{{$account->holder_name}}</h5>
                            @elseif(empty($account->holder_name))
                                <h5 class="report-text gray-text mb-0">{{__('Stripe / Paypal')}}</h5>
                            @else
                                <h5 class="report-text gray-text mb-0">{{$account->holder_name.' - '.$account->bank_name}}</h5>
                            @endif
                            <h5 class="report-text mb-0">{{\Auth::user()->priceFormat($account->total)}}</h5>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive pt-4">
                        <table class="table table-striped mb-0" id="report-dataTable">
                            <thead>
                            <tr>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Description')}}</th>
                                <th>{{__('Amount')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(!empty($reportData['revenues']))
                                @foreach ($reportData['revenues'] as $revenue)
                                    <tr class="font-style">
                                        <td>{{ Auth::user()->dateFormat($revenue->date) }}</td>
                                        <td>{{ Auth::user()->priceFormat($revenue->amount) }}</td>
                                        <td>{{$revenue->description}} </td>
                                    </tr>
                                @endforeach
                            @endif
                            @if(!empty($reportData['payments']))
                                @foreach ($reportData['payments'] as $payments)
                                    <tr class="font-style">
                                        <td>{{ Auth::user()->dateFormat($payments->date) }}</td>
                                        <td>{{ Auth::user()->priceFormat($payments->amount) }}</td>
                                        <td>{{!empty($payments->description)?$payments->description:'-'}} </td>
                                    </tr>
                                @endforeach
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
