@extends('layouts.admin')
@section('page-title')
    {{__('Dashboard')}}
@endsection
@push('script-page')
    <script>
            @if(\Auth::user()->can('show account dashboard'))
        var options = {
                series: [
                    {
                        name: "{{__('Income')}}",
                        data: {!! json_encode($incExpLineChartData['income']) !!}
                    },
                    {
                        name: "{{__('Expense')}}",
                        data: {!! json_encode($incExpLineChartData['expense']) !!}
                    }
                ],
                chart: {
                    height: 350,
                    type: 'line',
                    dropShadow: {
                        enabled: true,
                        color: '#000',
                        top: 18,
                        left: 7,
                        blur: 10,
                        opacity: 0.2
                    },
                    toolbar: {
                        show: false
                    }
                },
                colors: ['#77B6EA', '#545454'],
                dataLabels: {
                    enabled: true,
                },
                stroke: {
                    curve: 'smooth'
                },
                title: {
                    text: '',
                    align: 'left'
                },
                grid: {
                    borderColor: '#e7e7e7',
                    row: {
                        colors: ['#f3f3f3', 'transparent'], // takes an array which will be repeated on columns
                        opacity: 0.5
                    },
                },
                markers: {
                    size: 1
                },
                xaxis: {
                    categories: {!! json_encode($incExpLineChartData['day']) !!},
                    title: {
                        text: 'Days'
                    }
                },
                yaxis: {
                    title: {
                        text: '{{__('Amount')}}'
                    },

                },
                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    floating: true,
                    offsetY: -25,
                    offsetX: -5
                }
            };
        var chart = new ApexCharts(document.querySelector("#cash-flow"), options);
        chart.render();


        var SalesChart = {
            series: [
                {
                    name: "{{__('Income')}}",
                    data: {!! json_encode($incExpBarChartData['income']) !!}
                },
                {
                    name: "{{__('Expense')}}",
                    data: {!! json_encode($incExpBarChartData['expense']) !!}
                }
            ],
            colors: ['#77B6EA', '#545454'],
            chart: {
                type: 'bar',
                height: 430
            },
            plotOptions: {
                bar: {
                    horizontal: false,
                    dataLabels: {
                        position: 'top',
                    },
                }
            },
            dataLabels: {
                enabled: true,
                offsetX: -6,
                style: {
                    fontSize: '12px',
                    colors: ['#fff']
                }
            },
            stroke: {
                show: true,
                width: 1,
                colors: ['#fff']
            },
            xaxis: {
                categories: {!! json_encode($incExpBarChartData['month']) !!},
            },
        };
        var sales = new ApexCharts(document.querySelector("#incExpBarChart"), SalesChart);
        sales.render();


            var incomeCategories = {
                // series: [10,20],
                series:{!! json_encode($incomeCatAmount) !!},

                chart: {
                    width: '400px',
                    type: 'pie',
                },
                colors: {!! json_encode($incomeCategoryColor) !!},
                labels: {!! json_encode($incomeCategory) !!},

                plotOptions: {
                    pie: {
                        dataLabels: {
                            offset: -5
                        }
                    }
                },
                title: {
                    text: ""
                },
                dataLabels: {},
                legend: {
                    position: 'top',
                    align: 'end',
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                }

            };
            var incomeCategory = new ApexCharts(document.querySelector("#incomeByCategory"), incomeCategories);
            incomeCategory.render();


            var expenseCategories = {
                // series: [10,20],
                series: {!! json_encode($expenseCatAmount) !!},

                chart: {
                    width: '400px',
                    type: 'pie',
                },
                colors: {!! json_encode($expenseCategoryColor) !!},
                labels: {!! json_encode($expenseCategory) !!},

                plotOptions: {
                    pie: {
                        dataLabels: {
                            offset: -5
                        }
                    }
                },
                title: {
                    text: ""
                },
                dataLabels: {},
                legend: {
                    position: 'top',
                    align: 'end',
                },
                options: {
                    maintainAspectRatio: false,
                    responsive: true,
                }
            };
            var expenseCategory = new ApexCharts(document.querySelector("#expenseByCategory"), expenseCategories);
            expenseCategory.render();

        @endif
    </script>
@endpush
@section('content')

    @if(\Auth::user()->can('show account dashboard'))
        @if(\Auth::user()->type=='company')
            <div class="row">
                @if($constant['taxes'] <= 0)
                    <div class="col-3">
                        <div class="alert alert-danger text-xs">
                            {{__('Please add constant taxes. ')}}<a href="{{route('taxes.index')}}"><b>{{__('click here')}}</b></a>
                        </div>
                    </div>
                @endif
                @if($constant['category'] <= 0)
                    <div class="col-3">
                        <div class="alert alert-danger text-xs">
                            {{__('Please add constant category. ')}}<a href="{{route('product-category.index')}}"><b>{{__('click here')}}</b></a>
                        </div>
                    </div>
                @endif
                @if($constant['units'] <= 0)
                    <div class="col-3">
                        <div class="alert alert-danger text-xs">
                            {{__('Please add constant unit. ')}}<a href="{{route('product-unit.index')}}"><b>{{__('click here')}}</b></a>
                        </div>
                    </div>
                @endif

                @if($constant['bankAccount'] <= 0)
                    <div class="col-3">
                        <div class="alert alert-danger text-xs">
                            {{__('Please create bank account. ')}}<a href="{{route('bank-account.index')}}"><b>{{__('click here')}}</b></a>
                        </div>
                    </div>
                @endif
            </div>
        @endif
        <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="card card-box">
                    <div class="left-card">
                        <div class="icon-box bg-success"><i class="fas fa-users"></i></div>
                        <h4>{{__('Total Customers')}}</h4>
                    </div>
                    <div class="number-icon">{{\Auth::user()->countCustomers()}}</div>
                </div>
                <img src="{{ asset('assets/img/dot-icon.png') }}" alt="" class="dotted-icon">
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="card card-box">
                    <div class="left-card">
                        <div class="icon-box bg-warning"><i class="fas fa-user"></i></div>
                        <h4>{{__('Total Vendors')}}</h4>
                    </div>
                    <div class="number-icon">{{\Auth::user()->countVenders()}}</div>
                </div>
                <img src="{{ asset('assets/img/dot-icon.png') }}" alt="" class="dotted-icon">
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="card card-box">
                    <div class="left-card">
                        <div class="icon-box bg-primary"><i class="fas fa-money-bill"></i></div>
                        <h4>{{__('Total Invoices')}}</h4>
                    </div>
                    <div class="number-icon">{{\Auth::user()->countInvoices()}}</div>
                </div>
                <img src="{{ asset('assets/img/dot-icon.png') }}" alt="" class="dotted-icon">
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                <div class="card card-box">
                    <div class="left-card">
                        <div class="icon-box bg-dagner"><i class="fas fa-database"></i></div>
                        <h4>{{__('Total Bills')}}</h4>
                    </div>
                    <div class="number-icon">{{\Auth::user()->countBills()}}</div>
                </div>
                <img src="{{ asset('assets/img/dot-icon.png') }}" alt="" class="dotted-icon">
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div>
                    <h4 class="h4 font-weight-400 float-left">{{__('Cashflow')}}</h4>
                    <h6 class="last-day-text">{{__('Last')}} <span>{{__('15 days')}}</span></h6>
                </div>
                <div class="card bg-none">
                    <div id="cash-flow" class="chartjs-render-monitor custom-scroll"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xl-4 col-lg-4 col-md-4">
                <h4 class="h4 font-weight-400">{{__('Income Vs Expense')}}</h4>
                <div class="card bg-none dashboard-box-1">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <tbody class="list">
                            <tr>
                                <td>
                                    <h4 class="mb-0">{{__('Income')}}</h4>
                                    <h5 class="mb-0">{{__('Today')}}</h5>
                                </td>
                                <td>
                                    <h3 class="green-text">{{\Auth::user()->priceFormat(\Auth::user()->todayIncome())}}</h3>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h4 class="mb-0">{{__('Expense')}}</h4>
                                    <h5 class="mb-0">{{__('Today')}}</h5>
                                </td>
                                <td>
                                    <h3 class="red-text">{{\Auth::user()->priceFormat(\Auth::user()->todayExpense())}}</h3>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h4 class="mb-0">{{__('Income This')}}</h4>
                                    <h5 class="mb-0">{{__('Month')}}</h5>
                                </td>
                                <td>
                                    <h3 class="green-text">{{\Auth::user()->priceFormat(\Auth::user()->incomeCurrentMonth())}}</h3>
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    <h4 class="mb-0">{{__('Expense This')}}</h4>
                                    <h5 class="mb-0">{{__('Month')}}</h5>
                                </td>
                                <td>
                                    <h3 class="red-text">{{\Auth::user()->priceFormat(\Auth::user()->expenseCurrentMonth())}}</h3>
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-8 col-lg-8 col-md-8">
                <div class="">
                    <h4 class="h4 font-weight-400 float-left">{{__('Account Balance')}}</h4>
                </div>
                <div class="card bg-none dashboard-box-1">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead>
                            <tr>
                                <th>{{__('Bank')}}</th>
                                <th>{{__('Holder Name')}}</th>
                                <th>{{__('Balance')}}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($bankAccountDetail as $bankAccount)
                                <tr class="font-style">
                                    <td>{{$bankAccount->bank_name}}</td>
                                    <td>{{$bankAccount->holder_name}}</td>
                                    <td>{{\Auth::user()->priceFormat($bankAccount->opening_balance)}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="text-center">
                                            <h6>{{__('there is no account balance')}}</h6>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div>
                    <h4 class="h4 font-weight-400 float-left">{{__('Income & Expense')}}</h4>
                    <h6 class="last-day-text">{{__('Current Year').' - '.$currentYear}}</h6>
                </div>
                <div class="card bg-none">
                    <div class="custom-scroll">
                        <div id="incExpBarChart" height="250"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 col-md-12 col-12 col-sm-12">
                <div>
                    <h4 class="h4 font-weight-400 float-left">{{__('Income By Category')}}</h4>
                    <h6 class="last-day-text">{{__('Current Year').' - '.$currentYear}}</h6>
                </div>
                <div class="card bg-none">
                    <div class="card-body align-self-center height-440">
                        <div id="incomeByCategory"></div>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 col-md-12 col-12 col-sm-12">
                <div>
                    <h4 class="h4 font-weight-400 float-left">{{__('Expense By Category')}}</h4>
                    <h6 class="last-day-text">{{__('Current Year').' - '.$currentYear}}</h6>
                </div>
                <div class="card bg-none">
                    <div class="card-body align-self-center height-440">
                        <div id="expenseByCategory"></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-6 col-lg-6 col-md-6">
                <div class="">
                    <h4 class="h4 font-weight-400 float-left">{{__('Latest Income')}}</h4>
                    <a href="{{route('revenue.index')}}" class="more-text history-text float-right">{{__('View All')}}</a>
                </div>
                <div class="card bg-none dashboard-box-1">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                            <tr>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Customer')}}</th>
                                <th>{{__('Amount Due')}}</th>
                                <th>{{__('Description')}}</th>
                            </tr>
                            </thead>
                            <tbody class="list">
                            @forelse($latestIncome as $income)
                                <tr>
                                    <td>{{\Auth::user()->dateFormat($income->date)}}</td>
                                    <td>{{!empty($income->customer)?$income->customer->name:''}}</td>
                                    <td>{{\Auth::user()->priceFormat($income->amount)}}</td>
                                    <td>{{$income->description}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="text-center">
                                            <h6>{{__('there is no latest income')}}</h6>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6">
                <div class="">
                    <h4 class="h4 font-weight-400 float-left">{{__('Latest Expense')}}</h4>
                    <a href="{{route('payment.index')}}" class="more-text history-text float-right">{{__('View All')}}</a>
                </div>
                <div class="card bg-none dashboard-box-1">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                            <tr>
                                <th>{{__('Date')}}</th>
                                <th>{{__('Customer')}}</th>
                                <th>{{__('Amount Due')}}</th>
                                <th>{{__('Description')}}</th>
                            </tr>
                            </thead>
                            <tbody class="list">
                            @forelse($latestExpense as $expense)
                                <tr>
                                    <td>{{\Auth::user()->dateFormat($expense->date)}}</td>
                                    <td>{{!empty($expense->customer)?$expense->customer->name:''}}</td>
                                    <td>{{\Auth::user()->priceFormat($expense->amount)}}</td>
                                    <td>{{$expense->description}}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">
                                        <div class="text-center">
                                            <h6>{{__('there is no latest expense')}}</h6>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="">
                    <h4 class="h4 font-weight-400 float-left">{{__('Invoices')}}</h4>
                </div>
                <div class="card bg-none invo-tab dashboard-box-2">
                    <ul class="nav nav-tabs">
                        <li>
                            <a data-toggle="tab" href="#weekly_statistics" class="active">{{__('Weekly Statistics')}}</a>
                        </li>
                        <li class="annual-billing">
                            <a data-toggle="tab" href="#monthly_statistics" class="">{{__('Monthly Statistics')}}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="weekly_statistics" class="tab-pane in active">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <tbody class="list">
                                    <tr>
                                        <td>
                                            <h4 class="mb-0">{{__('Total')}}</h4>
                                            <h5 class="mb-0">{{__('Invoice Generated')}}</h5>
                                        </td>
                                        <td>
                                            <h3 class="green-text">{{\Auth::user()->priceFormat($weeklyInvoice['invoiceTotal'])}}</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h4 class="mb-0">{{__('Total')}}</h4>
                                            <h5 class="mb-0">{{__('Paid')}}</h5>
                                        </td>
                                        <td>
                                            <h3 class="red-text">{{\Auth::user()->priceFormat($weeklyInvoice['invoicePaid'])}}</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h4 class="mb-0">{{__('Total')}}</h4>
                                            <h5 class="mb-0">{{__('Due')}}</h5>
                                        </td>
                                        <td>
                                            <h3 class="green-text">{{\Auth::user()->priceFormat($weeklyInvoice['invoiceDue'])}}</h3>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="monthly_statistics" class="tab-pane">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0 ">
                                    <tbody class="list">
                                    <tr>
                                        <td>
                                            <h4 class="mb-0">{{__('Total')}}</h4>
                                            <h5 class="mb-0">{{__('Invoice Generated')}}</h5>
                                        </td>
                                        <td>
                                            <h3 class="green-text">{{\Auth::user()->priceFormat($monthlyInvoice['invoiceTotal'])}}</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h4 class="mb-0">{{__('Total')}}</h4>
                                            <h5 class="mb-0">{{__('Paid')}}</h5>
                                        </td>
                                        <td>
                                            <h3 class="red-text">{{\Auth::user()->priceFormat($monthlyInvoice['invoicePaid'])}}</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h4 class="mb-0">{{__('Total')}}</h4>
                                            <h5 class="mb-0">{{__('Due')}}</h5>
                                        </td>
                                        <td>
                                            <h3 class="green-text">{{\Auth::user()->priceFormat($monthlyInvoice['invoiceDue'])}}</h3>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-9 col-lg-8 col-md-6">
                <div class="">
                    <h4 class="h4 font-weight-400 float-left">{{__('Recent Invoices')}}</h4>
                </div>
                <div class="card bg-none dashboard-box-2">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{__('Customer')}}</th>
                                <th>{{__('Issue Date')}}</th>
                                <th>{{__('Due Date')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Status')}}</th>
                            </tr>
                            </thead>
                            <tbody class="list">
                            @forelse($recentInvoice as $invoice)
                                <tr>
                                    <td>{{\Auth::user()->invoiceNumberFormat($invoice->invoice_id)}}</td>
                                    <td>{{!empty($invoice->customer)? $invoice->customer->name:'' }} </td>
                                    <td>{{ Auth::user()->dateFormat($invoice->issue_date) }}</td>
                                    <td>{{ Auth::user()->dateFormat($invoice->due_date) }}</td>
                                    <td>{{\Auth::user()->priceFormat($invoice->getTotal())}}</td>
                                    <td>
                                        @if($invoice->status == 0)
                                            <span class="badge badge-pill badge-primary">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 1)
                                            <span class="badge badge-pill badge-warning">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 2)
                                            <span class="badge badge-pill badge-danger">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 3)
                                            <span class="badge badge-pill badge-info">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @elseif($invoice->status == 4)
                                            <span class="badge badge-pill badge-success">{{ __(\App\Invoice::$statues[$invoice->status]) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="text-center">
                                            <h6>{{__('there is no recent invoice')}}</h6>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xl-9 col-lg-8 col-md-6">
                <div class="">
                    <h4 class="h4 font-weight-400 float-left">{{__('Recent Bills')}}</h4>
                </div>
                <div class="card bg-none dashboard-box-2">
                    <div class="table-responsive">
                        <table class="table align-items-center mb-0">
                            <thead>
                            <tr>
                                <th>#</th>
                                <th>{{__('Vendor')}}</th>
                                <th>{{__('Bill Date')}}</th>
                                <th>{{__('Due Date')}}</th>
                                <th>{{__('Amount')}}</th>
                                <th>{{__('Status')}}</th>
                            </tr>
                            </thead>
                            <tbody class="list">
                            @forelse($recentBill as $bill)
                                <tr>
                                    <td>{{\Auth::user()->billNumberFormat($bill->bill_id)}}</td>
                                    <td>{{!empty($bill->vender)? $bill->vender->name:'' }} </td>
                                    <td>{{ Auth::user()->dateFormat($bill->bill_date) }}</td>
                                    <td>{{ Auth::user()->dateFormat($bill->due_date) }}</td>
                                    <td>{{\Auth::user()->priceFormat($bill->getTotal())}}</td>
                                    <td>
                                        @if($bill->status == 0)
                                            <span class="badge badge-pill badge-primary">{{ __(\App\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 1)
                                            <span class="badge badge-pill badge-warning">{{ __(\App\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 2)
                                            <span class="badge badge-pill badge-danger">{{ __(\App\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 3)
                                            <span class="badge badge-pill badge-info">{{ __(\App\Bill::$statues[$bill->status]) }}</span>
                                        @elseif($bill->status == 4)
                                            <span class="badge badge-pill badge-success">{{ __(\App\Bill::$statues[$bill->status]) }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6">
                                        <div class="text-center">
                                            <h6>{{__('there is no recent bill')}}</h6>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="col-xl-3 col-lg-4 col-md-6">
                <div class="">
                    <h4 class="h4 font-weight-400 float-left">{{__('Bills')}}</h4>
                </div>
                <div class="card bg-none invo-tab dashboard-box-2">
                    <ul class="nav nav-tabs">
                        <li>
                            <a data-toggle="tab" href="#bill_weekly_statistics" class="active">{{__('Weekly Statistics')}}</a>
                        </li>
                        <li class="annual-billing">
                            <a data-toggle="tab" href="#bill_monthly_statistics" class="">{{__('Monthly Statistics')}}</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="bill_weekly_statistics" class="tab-pane in active">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <tbody class="list">
                                    <tr>
                                        <td>
                                            <h4 class="mb-0">{{__('Total')}}</h4>
                                            <h5 class="mb-0">{{__('Bill Generated')}}</h5>
                                        </td>
                                        <td>
                                            <h3 class="green-text">{{\Auth::user()->priceFormat($weeklyBill['billTotal'])}}</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h4 class="mb-0">{{__('Total')}}</h4>
                                            <h5 class="mb-0">{{__('Paid')}}</h5>
                                        </td>
                                        <td>
                                            <h3 class="red-text">{{\Auth::user()->priceFormat($weeklyBill['billPaid'])}}</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h4 class="mb-0">{{__('Total')}}</h4>
                                            <h5 class="mb-0">{{__('Due')}}</h5>
                                        </td>
                                        <td>
                                            <h3 class="green-text">{{\Auth::user()->priceFormat($weeklyBill['billDue'])}}</h3>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div id="bill_monthly_statistics" class="tab-pane">
                            <div class="table-responsive">
                                <table class="table align-items-center mb-0">
                                    <tbody class="list">
                                    <tr>
                                        <td>
                                            <h4 class="mb-0">{{__('Total')}}</h4>
                                            <h5 class="mb-0">{{__('Bill Generated')}}</h5>
                                        </td>
                                        <td>
                                            <h3 class="green-text">{{\Auth::user()->priceFormat($monthlyBill['billTotal'])}}</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h4 class="mb-0">{{__('Total')}}</h4>
                                            <h5 class="mb-0">{{__('Paid')}}</h5>
                                        </td>
                                        <td>
                                            <h3 class="red-text">{{\Auth::user()->priceFormat($monthlyBill['billPaid'])}}</h3>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            <h4 class="mb-0">{{__('Total')}}</h4>
                                            <h5 class="mb-0">{{__('Due')}}</h5>
                                        </td>
                                        <td>
                                            <h3 class="green-text">{{\Auth::user()->priceFormat($monthlyBill['billDue'])}}</h3>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div>
                    <h4 class="h4 font-weight-400 float-left">{{__('Goal')}}</h4>
                    @forelse($goals as $goal)
                        @php
                            $total= $goal->target($goal->type,$goal->from,$goal->to,$goal->amount)['total'];
                            $percentage=$goal->target($goal->type,$goal->from,$goal->to,$goal->amount)['percentage'];
                        @endphp
                        <div class="card pb-0 mb-4">
                            <div class="row">
                                <div class="col-md-2 col-sm-6">
                                    <div class="p-4">
                                        <h5 class="report-text gray-text mb-0">{{__('Name')}}</h5>
                                        <h5 class="report-text mb-0">{{$goal->name}}</h5>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <div class="p-4">
                                        <h5 class="report-text gray-text mb-0">{{__('Type')}}</h5>
                                        <h5 class="report-text mb-0">{{ __(\App\Goal::$goalType[$goal->type]) }}</h5>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="p-4">
                                        <h5 class="report-text gray-text mb-0">{{__('Duration')}}</h5>
                                        <h5 class="report-text mb-0">{{$goal->from .' To '.$goal->to}}</h5>
                                    </div>
                                </div>
                                <div class="col-md-3 col-sm-6">
                                    <div class="p-4">
                                        <h5 class="report-text gray-text mb-0">{{__('Target')}}</h5>
                                        <h5 class="report-text mb-0">{{\Auth::user()->priceFormat($total).' of '. \Auth::user()->priceFormat($goal->amount)}}</h5>
                                    </div>
                                </div>
                                <div class="col-md-2 col-sm-6">
                                    <div class="p-4">
                                        <h5 class="report-text gray-text mb-0">{{__('Progress')}}</h5>
                                        <h5 class="report-text mb-0">{{number_format($goal->target($goal->type,$goal->from,$goal->to,$goal->amount)['percentage'], Utility::getValByName('decimal_number'), '.', '')}}%</h5>
                                    </div>
                                </div>
                                <div class="col-12 px-4">
                                    <div class="progress-wrapper pt-0">
                                        <div class="progress progress-xs mb-0 w-100">
                                            <div class="progress-bar bg-warning" role="progressbar" aria-valuenow="{{number_format($goal->target($goal->type,$goal->from,$goal->to,$goal->amount)['percentage'], Utility::getValByName('decimal_number'), '.', '')}}" aria-valuemin="0" aria-valuemax="100" style="width: {{number_format($goal->target($goal->type,$goal->from,$goal->to,$goal->amount)['percentage'], Utility::getValByName('decimal_number'), '.', '')}}%;"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="card pb-0">
                            <div class="card-body text-center">
                                <h6>{{__('There is no goal.')}}</h6>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-md-12 text-center">
                <h4 class="text-danger">{{__('Permission Denied')}}</h4>
            </div>
        </div>
    @endif
@endsection
