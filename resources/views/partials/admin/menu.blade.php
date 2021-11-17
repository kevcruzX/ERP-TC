@php
    $logo=asset(Storage::url('uploads/logo/'));
    $company_logo=Utility::getValByName('company_logo');
    $company_small_logo=Utility::getValByName('company_small_logo');
@endphp

<div class="sidenav custom-sidenav" id="sidenav-main">
    <!-- Sidenav header -->
    <div class="sidenav-header d-flex align-items-center">
        <a class="navbar-brand" href="{{ route('dashboard') }}">
            <img src="{{$logo.'/'.(isset($company_logo) && !empty($company_logo)?$company_logo:'logo.png')}}" class="navbar-brand-img"/>
        </a>
        <div class="ml-auto">
            <div class="sidenav-toggler sidenav-toggler-dark d-md-none" data-action="sidenav-unpin" data-target="#sidenav-main">
                <div class="sidenav-toggler-inner">
                    <i class="sidenav-toggler-line bg-white"></i>
                    <i class="sidenav-toggler-line bg-white"></i>
                    <i class="sidenav-toggler-line bg-white"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="scrollbar-inner">
        <div class="div-mega">
            @if(\Auth::user()->type != 'client')
                <ul class="navbar-nav navbar-nav-docs">
                    @if( Gate::check('show hrm dashboard') || Gate::check('show project dashboard') || Gate::check('show account dashboard'))
                        <li class="nav-item">
                            <a class="nav-link {{ (Request::segment(1) == 'account-dashboard' || Request::segment(1) == 'project-dashboard' || Request::segment(1) == 'hrm-dashboard')?' active':'collapsed'}}" href="#navbar-dashboard" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'account-dashboard' || Request::segment(1) == 'project-dashboard' || Request::segment(1) == 'hrm-dashboard')?'true':'false'}}" aria-controls="navbar-dashboard">
                                <i class="fa fa-fire"></i>{{__('Dashboard')}}
                                <i class="fas fa-sort-up"></i>
                            </a>
                            <div class="collapse {{ (Request::segment(1) == 'account-dashboard' || Request::segment(1) == 'project-dashboard' || Request::segment(1) == 'hrm-dashboard')?'show':''}}" id="navbar-dashboard">
                                <ul class="nav flex-column">
                                    @if(\Auth::user()->show_account() == 1)
                                        @can('show account dashboard')
                                            <li class="nav-item {{ (Request::route()->getName() == 'dashboard') ? ' active' : '' }}">
                                                <a href="{{route('dashboard')}}" class="nav-link">{{ __("Account Dashboard") }}</a>
                                            </li>
                                        @endif
                                    @endif
                                    @if(\Auth::user()->show_hrm() == 1)
                                        @can('show hrm dashboard')
                                            <li class="nav-item {{ (Request::route()->getName() == 'hrm.dashboard') ? ' active' : '' }}">
                                                <a href="{{route('hrm.dashboard')}}" class="nav-link">{{ __("HRM Dashboard") }}</a>
                                            </li>
                                        @endcan
                                    @endif
                                    @if(\Auth::user()->show_project() == 1)
                                        @can('show project dashboard')
                                            <li class="nav-item {{ (Request::route()->getName() == 'project.dashboard') ? ' active' : '' }}">
                                                <a href="{{route('project.dashboard')}}" class="nav-link">{{ __("Project Dashboard") }}</a>
                                            </li>
                                        @endcan
                                    @endif

                                </ul>
                            </div>
                        </li>
                    @endif
                    @can('manage customer proposal')
                        <li class="nav-item">
                            <a href="{{route('customer.proposal')}}" class="nav-link {{ (Request::route()->getName() == 'customer.proposal' || Request::route()->getName() == 'customer.proposal.show') ? ' active' : '' }}">
                                <i class="fas fa-file"></i>{{__('Proposal')}}
                            </a>
                        </li>
                    @endcan
                    @can('manage customer invoice')
                        <li class="nav-item">
                            <a href="{{route('customer.invoice')}}" class="nav-link {{ (Request::route()->getName() == 'customer.invoice' || Request::route()->getName() == 'customer.invoice.show') ? ' active' : '' }} ">
                                <i class="fas fa-file"></i>{{__('Invoice')}}
                            </a>
                        </li>
                    @endcan
                    @can('manage customer payment')
                        <li class="nav-item">
                            <a href="{{route('customer.payment')}}" class="nav-link {{ (Request::route()->getName() == 'customer.payment') ? ' active' : '' }} ">
                                <i class="fas fa-money-bill-alt"></i>{{__('Payment')}}
                            </a>
                        </li>
                    @endcan
                    @can('manage customer transaction')
                        <li class="nav-item">
                            <a href="{{route('customer.transaction')}}" class="nav-link {{ (Request::route()->getName() == 'customer.transaction') ? ' active' : '' }}">
                                <i class="fas fa-history"></i>{{__('Transaction')}}
                            </a>
                        </li>
                    @endcan
                    @can('manage vender bill')
                        <li class="nav-item">
                            <a href="{{route('vender.bill')}}" class="nav-link {{ (Request::route()->getName() == 'vender.bill' || Request::route()->getName() == 'vender.bill.show') ? ' active' : '' }} ">
                                <i class="fas fa-file"></i>{{__('Bill')}}
                            </a>
                        </li>
                    @endcan
                    @can('manage vender payment')
                        <li class="nav-item">
                            <a href="{{route('vender.payment')}}" class="nav-link {{ (Request::route()->getName() == 'vender.payment') ? ' active' : '' }} ">
                                <i class="fas fa-money-bill-alt"></i>{{__('Payment')}}
                            </a>
                        </li>
                    @endcan
                    @can('manage vender transaction')
                        <li class="nav-item">
                            <a href="{{route('vender.transaction')}}" class="nav-link {{ (Request::route()->getName() == 'vender.transaction') ? ' active' : '' }}">
                                <i class="fas fa-history"></i>{{__('Transaction')}}
                            </a>
                        </li>
                    @endcan
                    @if(\Auth::user()->type=='super admin')
                    @else
                        @if( Gate::check('manage user') || Gate::check('manage role'))
                            <li class="nav-item">
                                <a class="nav-link {{ (Request::segment(1) == 'users' || Request::segment(1) == 'roles' || Request::segment(1) == 'clients')?' active':'collapsed'}}" href="#navbar-staff" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'users' || Request::segment(1) == 'roles' || Request::segment(1) == 'clients')?'true':'false'}}" aria-controls="navbar-staff">
                                    <i class="fa fa-users"></i>{{__('Staff')}}
                                    <i class="fas fa-sort-up"></i>
                                </a>
                                <div class="collapse {{ (Request::segment(1) == 'users' || Request::segment(1) == 'roles' || Request::segment(1) == 'clients')?'show':''}}" id="navbar-staff">
                                    <ul class="nav flex-column">
                                        @can('manage user')
                                            <li class="nav-item {{ (Request::route()->getName() == 'users.index' || Request::route()->getName() == 'users.create' || Request::route()->getName() == 'users.edit') ? ' active' : '' }}">
                                                <a href="{{ route('users.index') }}" class="nav-link">{{ __('User') }}</a>
                                            </li>
                                        @endcan
                                        @can('manage role')
                                            <li class="nav-item {{ (Request::route()->getName() == 'roles.index' || Request::route()->getName() == 'roles.create' || Request::route()->getName() == 'roles.edit') ? ' active' : '' }}">
                                                <a href="{{route('roles.index')}}" class="nav-link">{{ __('Role') }}</a>
                                            </li>
                                        @endcan
                                        @can('manage client')
                                            <li class="nav-item {{ (Request::route()->getName() == 'clients.index' || Request::segment(1) == 'clients' || Request::route()->getName() == 'clients.edit') ? ' active' : '' }}">
                                                <a href="{{ route('clients.index') }}" class="nav-link">{{ __('Client') }}</a>
                                            </li>
                                        @endcan
                                    </ul>
                                </div>
                            </li>
                        @endif
                        @if(Gate::check('manage product & service'))
                            <li class="nav-item">
                                <a href="{{ route('productservice.index') }}" class="nav-link {{ (Request::segment(1) == 'productservice')?'active':''}}">
                                    <i class="fas fa-shopping-cart"></i>{{__('Product & Service')}}
                                </a>
                            </li>
                        @endif

                        @if(\Auth::user()->show_crm() == 1)
                            @if( Gate::check('manage lead') || Gate::check('manage deal') || Gate::check('manage form builder'))
                                <li class="nav-item">
                                    <a class="nav-link {{ (Request::segment(1) == 'stages' || Request::segment(1) == 'labels' || Request::segment(1) == 'sources' || Request::segment(1) == 'lead_stages' || Request::segment(1) == 'pipelines' || Request::segment(1) == 'deals' || Request::segment(1) == 'leads'  || Request::segment(1) == 'form_builder' || Request::segment(1) == 'form_response')?' active':'collapsed'}}" href="#navbar-crm" data-toggle="collapse" role="button"
                                       aria-expanded="{{ (Request::segment(1) == 'stages' || Request::segment(1) == 'labels' || Request::segment(1) == 'sources' || Request::segment(1) == 'lead_stages' || Request::segment(1) == 'pipelines' || Request::segment(1) == 'leads'  || Request::segment(1) == 'form_builder' || Request::segment(1) == 'form_response' || Request::segment(1) == 'deals' || Request::segment(1) == 'pipelines')?'true':'false'}}" aria-controls="navbar-crm">
                                        <i class="fa fa-filter"></i>{{__('CRM')}}
                                        <i class="fas fa-sort-up"></i>
                                    </a>
                                    <div class="collapse {{ (Request::segment(1) == 'stages' || Request::segment(1) == 'labels' || Request::segment(1) == 'sources' || Request::segment(1) == 'lead_stages' || Request::segment(1) == 'leads'  || Request::segment(1) == 'form_builder' || Request::segment(1) == 'form_response' || Request::segment(1) == 'deals' || Request::segment(1) == 'pipelines')?'show':''}}" id="navbar-crm">
                                        <ul class="nav flex-column">
                                            @can('manage lead')
                                                <li class="nav-item {{ (Request::route()->getName() == 'leads.list' || Request::route()->getName() == 'leads.index' || Request::route()->getName() == 'leads.show') ? ' active' : '' }}">
                                                    <a href="{{ route('leads.index') }}" class="nav-link">{{ __('Leads') }}</a>
                                                </li>
                                            @endcan
                                            @can('manage deal')
                                                <li class="nav-item {{ (Request::route()->getName() == 'deals.list' || Request::route()->getName() == 'deals.index' || Request::route()->getName() == 'deals.show') ? ' active' : '' }}">
                                                    <a href="{{route('deals.index')}}" class="nav-link">{{ __('Deals') }}</a>
                                                </li>
                                            @endcan
                                            @can('manage form builder')
                                                <li class="nav-item {{ (Request::segment(1) == 'form_builder' || Request::segment(1) == 'form_response')?'active open':''}}">
                                                    <a href="{{route('form_builder.index')}}" class="nav-link">{{__('Form Builder')}}</a>
                                                </li>
                                            @endcan
                                            @if(Gate::check('manage lead stage') || Gate::check('manage pipeline') ||Gate::check('manage source') ||Gate::check('manage label') || Gate::check('manage stage'))
                                                <li class="nav-item">
                                                    <div class="collapse show" id="crm-setup-navbar" style="">
                                                        <ul class="nav flex-column">
                                                            <ul class="nav flex-column">
                                                                <li class="nav-item submenu-li ">
                                                                    <a class="nav-link {{(Request::segment(1) == 'stages' || Request::segment(1) == 'labels' || Request::segment(1) == 'sources' || Request::segment(1) == 'lead_stages' || Request::segment(1) == 'pipelines' || Request::segment(1) == 'product-category' || Request::segment(1) == 'product-unit' || Request::segment(1) == 'payment-method' || Request::segment(1) == 'custom-field' || Request::segment(1) == 'chart-of-account-type')? 'active' :'collapsed'}}"
                                                                       href="#crm-setup-nav" data-toggle="collapse" role="button"
                                                                       aria-expanded="{{(Request::segment(1) == 'stages' || Request::segment(1) == 'labels' || Request::segment(1) == 'sources' || Request::segment(1) == 'lead_stages' || Request::segment(1) == 'pipelines' || Request::segment(1) == 'product-category' || Request::segment(1) == 'product-unit' || Request::segment(1) == 'payment-method' || Request::segment(1) == 'custom-field' || Request::segment(1) == 'chart-of-account-type')? 'true' :'false'}}"
                                                                       aria-controls="navbar-getting-started1">
                                                                        {{__('Setup')}}
                                                                        <i class="fas fa-sort-up"></i>
                                                                    </a>
                                                                    <div
                                                                        class="submenu-ul {{(Request::segment(1) == 'stages' || Request::segment(1) == 'labels' || Request::segment(1) == 'sources' || Request::segment(1) == 'lead_stages' || Request::segment(1) == 'pipelines' || Request::segment(1) == 'product-category' || Request::segment(1) == 'product-unit' || Request::segment(1) == 'payment-method' || Request::segment(1) == 'custom-field' || Request::segment(1) == 'chart-of-account-type')? 'show' :'collapse'}}"
                                                                        id="crm-setup-nav" style="">
                                                                        <ul class="nav flex-column">
                                                                            @can('manage pipeline')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'pipelines.index' ) ? ' active' : '' }}">
                                                                                    <a href="{{ route('pipelines.index') }}" class="nav-link">{{ __('Pipeline') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('manage lead stage')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'lead_stages.index' ) ? 'active' : '' }}">
                                                                                    <a href="{{route('lead_stages.index')}}" class="nav-link">{{ __('Lead Stages') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('manage stage')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'stages.index' ) ? 'active' : '' }}">
                                                                                    <a href="{{route('stages.index')}}" class="nav-link">{{ __('Deal Stages') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('manage source')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'sources.index' ) ? ' active' : '' }}">
                                                                                    <a href="{{route('sources.index')}}" class="nav-link">{{ __('Sources') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('manage label')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'labels.index' ) ? 'active' : '' }}">
                                                                                    <a href="{{route('labels.index')}}" class="nav-link">{{ __('Labels') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                        </ul>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif
                        @endif
                        @if(\Auth::user()->show_project() == 1)
                            @if( Gate::check('manage project'))
                                <li class="nav-item">
                                    <a class="nav-link {{ (Request::segment(1) == 'bugs-report' || Request::segment(1) == 'bugstatus' || Request::segment(1) == 'project-task-stages' || Request::segment(1) == 'calendar' || Request::segment(1) == 'timesheet-list' || Request::segment(1) == 'taskboard' || Request::segment(1) == 'timesheet-list' || Request::segment(1) == 'taskboard' || Request::segment(1) == 'project' || Request::segment(1) == 'projects') ? 'active' : 'collapsed'}}" href="#taskGo"
                                       data-toggle="collapse" role="button"
                                       aria-expanded="{{(Request::segment(1) == 'bugs-report' || Request::segment(1) == 'bugstatus' || Request::segment(1) == 'calendar' || Request::segment(1) == 'timesheet-list' || Request::segment(1) == 'taskBoard' || Request::segment(1) == 'timesheet-list' || Request::segment(1) == 'taskboard' || Request::segment(1) == 'projects' || Request::segment(1) == 'project' || Request::segment(1) == 'project-task-stages') ? 'true' : 'false'}}" aria-controls="user">
                                        <i class="fa fa-project-diagram"></i>{{__('Project')}}
                                        <i class="fas fa-sort-up"></i>
                                    </a>
                                    <div
                                        class="collapse {{ (Request::segment(1) == 'bugs-report' || Request::segment(1) == 'project' || Request::segment(1) == 'bugstatus' || Request::segment(1) == 'project-task-stages' || Request::segment(1) == 'calendar' || Request::segment(1) == 'timesheet-list' || Request::segment(1) == 'taskboard' || Request::segment(1) == 'timesheet-list' || Request::segment(1) == 'taskboard' || Request::segment(1) == 'project' || Request::segment(1) == 'projects') ? 'show' : ''}}"
                                        id="taskGo" style="">
                                        <ul class="nav flex-column">
                                            @can('manage project')
                                                <li class="nav-item  {{Request::segment(1) == 'project' || Request::route()->getName() == 'projects.list' || Request::route()->getName() == 'projects.list' ||Request::route()->getName() == 'projects.index' || Request::route()->getName() == 'projects.show' || request()->is('projects/*') ? 'active' : ''}}">
                                                    <a href="{{route('projects.index')}}" class="nav-link">
                                                        {{ __('Projects') }}
                                                    </a>
                                                </li>
                                            @endcan
                                            @can('manage project task')
                                                <li class="nav-item {{ (request()->is('taskboard*') ? 'active' : '')}}">
                                                    <a href="{{ route('taskBoard.view', 'list') }}" class="nav-link">{{__('Tasks')}}</a>
                                                </li>
                                            @endcan
                                            @can('manage timesheet')

                                                <li class="nav-item {{ (request()->is('timesheet-list*') ? 'active' : '')}}">
                                                    <a href="{{route('timesheet.list')}}" class="nav-link">{{__('Timesheet')}}</a>
                                                </li>
                                                @if(\Auth::user()->type =='company')
                                                @endif
                                            @endcan
                                            @can('manage bug report')
                                                <li class="nav-item {{ (request()->is('bugs-report*') ? 'active' : '')}}">
                                                    <a href="{{route('bugs.view','list')}}" class="nav-link">{{__('Bug')}}</a>
                                                </li>
                                            @endcan
                                            @can('manage project task')
                                                <li class="nav-item {{ (request()->is('calendar*') ? 'active' : '')}}">
                                                    <a href="{{ route('task.calendar',['all']) }}" class="nav-link">{{__('Task Calender')}}</a>
                                                </li>
                                            @endcan
                                            @if(Gate::check('manage project task stage') || Gate::check('manage bug status'))
                                                <li class="nav-item">
                                                    <div class="collapse show" id="taskgo_constants" style="">
                                                        <ul class="nav flex-column">
                                                            <ul class="nav flex-column">
                                                                <li class="nav-item submenu-li">
                                                                    <a class="nav-link {{ (Request::segment(1) == 'bugstatus' || Request::segment(1) == 'project-task-stages') ? 'active' : 'collapsed'}}" href="#taskgo_navbar_constants" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'project-task-stages') ? 'true' : 'false'}}" aria-controls="navbar-getting-started1">
                                                                        {{__('Setup')}}
                                                                        <i class="fas fa-sort-up"></i>
                                                                    </a>
                                                                    <div class="submenu-ul {{ (Request::segment(1) == 'bugstatus' || Request::segment(1) == 'project-task-stages') ? 'show' : 'collapse'}}" id="taskgo_navbar_constants" style="">
                                                                        <ul class="nav flex-column">
                                                                            @can('manage project task stage')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'project-task-stages.index') ? 'active' : '' }}">
                                                                                    <a class="nav-link" href="{{route('project-task-stages.index')}}">{{__('Project Task Stages')}}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('manage bug status')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'bugstatus.index') ? 'active' : '' }}">
                                                                                    <a class="nav-link" href="{{route('bugstatus.index')}}">{{__('Bug Status')}}</a>
                                                                                </li>
                                                                            @endcan
                                                                        </ul>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif
                        @endif
                    <!-- For Hrm -->

                        @if(\Auth::user()->show_hrm() == 1)
                            @if( Gate::check('manage employee') || Gate::check('manage setsalary'))
                                <li class="nav-item">
                                    <a class="nav-link {{ (Request::segment(1) == 'holiday-calender' || Request::segment(1) == 'reports-monthly-attendance' || Request::segment(1) == 'reports-leave' || Request::segment(1) == 'reports-payroll' || Request::segment(1) == 'leavetype' || Request::segment(1) == 'leave' || Request::segment(1) == 'attendanceemployee' || Request::segment(1) == 'document-upload' || Request::segment(1) == 'document' || Request::segment(1) == 'branch' || Request::segment(1) == 'department' || Request::segment(1) == 'designation' || Request::segment(1) == 'employee' || Request::segment(1) == 'leave_requests' || Request::segment(1) == 'holidays' || Request::segment(1) == 'policies' || Request::segment(1) == 'leave_calender' || Request::segment(1) == 'award' || Request::segment(1) == 'transfer' || Request::segment(1) == 'resignation' || Request::segment(1) == 'travel' || Request::segment(1) == 'promotion' || Request::segment(1) == 'complaint' || Request::segment(1) == 'warning' || Request::segment(1) == 'termination' || Request::segment(1) == 'announcement' || Request::segment(1) == 'job' || Request::segment(1) == 'job-application' || Request::segment(1) == 'candidates-job-applications' || Request::segment(1) == 'job-onboard' || Request::segment(1) == 'custom-question' || Request::segment(1) == 'interview-schedule' || Request::segment(1) == 'career' || Request::segment(1) == 'holiday' || Request::segment(1) == 'setsalary' || Request::segment(1) == 'payslip' || Request::segment(1) == 'paysliptype' || Request::segment(1) == 'company-policy' || Request::segment(1) == 'job-stage' || Request::segment(1) == 'job-category' || Request::segment(1) == 'terminationtype' || Request::segment(1) == 'awardtype' || Request::segment(1) == 'trainingtype' || Request::segment(1) == 'goaltype' || Request::segment(1) == 'paysliptype' || Request::segment(1) == 'allowanceoption' || Request::segment(1) == 'loanoption' || Request::segment(1) == 'deductionoption')?'active':'collapsed'}}"
                                       href="#hrm" data-toggle="collapse" role="button"
                                       aria-expanded="{{ (Request::segment(1) == 'holiday-calender' || Request::segment(1) == 'reports-monthly-attendance' || Request::segment(1) == 'reports-leave' || Request::segment(1) == 'reports-payroll' || Request::segment(1) == 'leavetype' || Request::segment(1) == 'leave' || Request::segment(1) == 'attendanceemployee' || Request::segment(1) == 'document-upload' || Request::segment(1) == 'document' || Request::segment(1) == 'branch' || Request::segment(1) == 'department' || Request::segment(1) == 'designation' || Request::segment(1) == 'employee' || Request::segment(1) == 'trainer' || Request::segment(1) == 'training' || Request::segment(1) == 'meeting' || Request::segment(1) == 'event' || Request::segment(1) == 'account-assets' || Request::segment(1) == 'indicator' || Request::segment(1) == 'appraisal' || Request::segment(1) == 'goaltracking' || Request::segment(1) == 'setsalary' || Request::segment(1) == 'payslip' || Request::segment(1) == 'company-policy' || Request::segment(1) == 'job-stage' || Request::segment(1) == 'job-category' || Request::segment(1) == 'terminationtype' || Request::segment(1) == 'awardtype' || Request::segment(1) == 'trainingtype' || Request::segment(1) == 'goaltype' || Request::segment(1) == 'paysliptype' || Request::segment(1) == 'allowanceoption' || Request::segment(1) == 'loanoption' || Request::segment(1) == 'deductionoption')?'true':'false'}}"
                                       aria-controls="fleet">
                                        <i class="fas fa-user"></i>{{__('HRM')}}
                                        <i class="fas fa-sort-up"></i>
                                    </a>
                                    <div
                                        class="collapse {{ (Request::segment(1) == 'holiday-calender' || Request::segment(1) == 'reports-monthly-attendance' || Request::segment(1) == 'reports-leave' || Request::segment(1) == 'reports-payroll' || Request::segment(1) == 'leavetype' || Request::segment(1) == 'leave' || Request::segment(1) == 'attendanceemployee' || Request::segment(1) == 'document-upload' || Request::segment(1) == 'document' || Request::segment(1) == 'branch' || Request::segment(1) == 'department' || Request::segment(1) == 'designation' || Request::segment(1) == 'employee' || Request::segment(1) == 'leave_requests' || Request::segment(1) == 'holidays' || Request::segment(1) == 'policies' || Request::segment(1) == 'leave_calender' || Request::segment(1) == 'award' || Request::segment(1) == 'transfer' || Request::segment(1) == 'resignation' || Request::segment(1) == 'travel' || Request::segment(1) == 'promotion' || Request::segment(1) == 'complaint' || Request::segment(1) == 'warning' || Request::segment(1) == 'termination' || Request::segment(1) == 'announcement' || Request::segment(1) == 'job' || Request::segment(1) == 'job-application' || Request::segment(1) == 'candidates-job-applications' || Request::segment(1) == 'job-onboard' || Request::segment(1) == 'custom-question' || Request::segment(1) == 'interview-schedule' || Request::segment(1) == 'career' || Request::segment(1) == 'holiday' || Request::segment(1) == 'trainer' || Request::segment(1) == 'training' || Request::segment(1) == 'meeting' || Request::segment(1) == 'event' || Request::segment(1) == 'account-assets' || Request::segment(1) == 'indicator' || Request::segment(1) == 'appraisal' || Request::segment(1) == 'goaltracking' || Request::segment(1) == 'setsalary' || Request::segment(1) == 'payslip' || Request::segment(1) == 'company-policy' || Request::segment(1) == 'job-stage' || Request::segment(1) == 'job-category' || Request::segment(1) == 'terminationtype' || Request::segment(1) == 'awardtype' || Request::segment(1) == 'trainingtype' || Request::segment(1) == 'goaltype' || Request::segment(1) == 'paysliptype' || Request::segment(1) == 'allowanceoption' || Request::segment(1) == 'loanoption' || Request::segment(1) == 'deductionoption')?'show':''}}"
                                        id="hrm" style="">
                                        <ul class="nav flex-column">
                                            <li class="nav-item">
                                                <div class="collapse show" id="navbar-accounting8" style="">
                                                    <ul class="nav flex-column">
                                                        <ul class="nav flex-column">
                                                            <li class="nav-item  {{ (Request::segment(1) == 'employee' ? 'active' : '')}}">
                                                                @if(\Auth::user()->type =='Employee')
                                                                    @php
                                                                        $employee=App\Employee::where('user_id',\Auth::user()->id)->first();
                                                                    @endphp
                                                                    <a href="{{route('employee.show',\Illuminate\Support\Facades\Crypt::encrypt(\Auth::user()->id))}}" class="nav-link   {{ (request()->is('employee*') ? 'active' : '')}}">
                                                                        {{ __('Employee') }}
                                                                    </a>
                                                                @else
                                                                    <a href="{{route('employee.index')}}" class="nav-link">

                                                                        {{ __('Employee') }}
                                                                    </a>
                                                                @endif
                                                            </li>
                                                            <li class="nav-item submenu-li ">
                                                                <a class="nav-link {{ (Request::segment(1) == 'setsalary' || Request::segment(1) == 'payslip') ? 'active' : 'collapsed'}}" href="#navbar-accounting8-users" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'setsalary' || Request::segment(1) == 'payslip') ? 'true' : 'false'}}" aria-controls="navbar-getting-started1">
                                                                    {{__('Payroll')}}
                                                                    <i class="fas fa-sort-up"></i>
                                                                </a>
                                                                <div class="submenu-ul {{ (Request::segment(1) == 'setsalary' || Request::segment(1) == 'payslip') ? 'show' : 'collapse'}}" id="navbar-accounting8-users" style="">
                                                                    <ul class="nav flex-column">
                                                                        @can('manage set salary')
                                                                            <li class="nav-item {{ (request()->is('setsalary*') ? 'active' : '')}}">
                                                                                <a href="{{route('setsalary.index')}}" class="nav-link">{{__('Set salary')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage pay slip')
                                                                            <li class="nav-item {{ (request()->is('payslip*') ? 'active' : '')}}">
                                                                                <a href="{{route('payslip.index')}}" class="nav-link">{{__('Payslip')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                    </ul>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </ul>
                                                </div>
                                            </li>


                                            <li class="nav-item">
                                                <div class="collapse show" id="navbar-hr" style="">
                                                    <ul class="nav flex-column">
                                                        <li class="nav-item submenu-li ">
                                                            <a class="nav-link {{ (Request::segment(1) == 'leave' || Request::segment(1) == 'attendanceemployee') ? 'active' :'collapsed'}}" href="#navbar-hr-leave_management" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'leave' || Request::segment(1) == 'attendanceemployee') ? 'true' :'false'}}" aria-controls="navbar-getting-started1">
                                                                {{__('Leave Management')}}
                                                                <i class="fas fa-sort-up"></i>
                                                            </a>
                                                            <div class="submenu-ul {{ (Request::segment(1) == 'leave' || Request::segment(1) == 'attendanceemployee') ? 'show' :'collapse'}}" id="navbar-hr-leave_management" style="">
                                                                <ul class="nav flex-column">
                                                                    @can('manage leave')
                                                                        <li class="nav-item {{ (Request::route()->getName() == 'leave.index') ?'active' :''}}">
                                                                            <a href="{{route('leave.index')}}" class="nav-link">{{__('Manage Leave')}}</a>
                                                                        </li>
                                                                    @endcan
                                                                    @can('manage attendance')
                                                                        <li class="nav-item">
                                                                            <div class="collapse show" id="attendance-navbar" style="">
                                                                                <ul class="nav flex-column">
                                                                                    <ul class="nav flex-column">
                                                                                        <li class="nav-item submenu-li ">
                                                                                            <a class="nav-link {{ (Request::segment(1) == 'attendanceemployee') ? 'active' : 'collapsed'}}" href="#navbar-attendance" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'attendanceemployee') ? 'true' : 'false'}}" aria-controls="navbar-getting-started1">
                                                                                                {{__('Attendance')}}
                                                                                                <i class="fas fa-sort-up"></i>
                                                                                            </a>
                                                                                            <div class="submenu-ul {{ (Request::segment(1) == 'attendanceemployee') ? 'show' : 'collapse'}}" id="navbar-attendance" style="">
                                                                                                <ul class="nav flex-column">
                                                                                                    <li class="nav-item {{ (Request::route()->getName() == 'attendanceemployee.index' ? 'active' : '')}}">
                                                                                                        <a href="{{route('attendanceemployee.index')}}" class="nav-link">{{__('Marked Attendance')}}</a>
                                                                                                    </li>
                                                                                                    @can('create attendance')
                                                                                                        <li class="nav-item {{ (Request::route()->getName() == 'attendanceemployee.bulkattendance' ? 'active' : '')}}">
                                                                                                            <a href="{{ route('attendanceemployee.bulkattendance') }}" class="nav-link">{{__('Bulk Attendance')}}</a>
                                                                                                        </li>
                                                                                                    @endcan
                                                                                                </ul>
                                                                                            </div>
                                                                                        </li>
                                                                                    </ul>
                                                                                </ul>
                                                                            </div>
                                                                        </li>
                                                                    @endcan
                                                                </ul>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>

                                            <li class="nav-item">
                                                <div class="collapse show" id="performance-navbar" style="">
                                                    <ul class="nav flex-column">
                                                        <ul class="nav flex-column">
                                                            <li class="nav-item submenu-li ">
                                                                <a class="nav-link {{ (Request::segment(1) == 'indicator' || Request::segment(1) == 'appraisal' || Request::segment(1) == 'goaltracking') ? 'active' : 'collapsed'}}" href="#navbar-performance" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'indicator' || Request::segment(1) == 'appraisal' || Request::segment(1) == 'goaltracking') ? 'true' : 'false'}}" aria-controls="navbar-getting-started1">
                                                                    {{__('Performance')}}
                                                                    <i class="fas fa-sort-up"></i>
                                                                </a>
                                                                <div class="submenu-ul {{ (Request::segment(1) == 'indicator' || Request::segment(1) == 'appraisal' || Request::segment(1) == 'goaltracking') ? 'show' : 'collapse'}}" id="navbar-performance" style="">
                                                                    <ul class="nav flex-column">
                                                                        @can('manage indicator')
                                                                            <li class="nav-item {{ (request()->is('indicator*') ? 'active' : '')}}">
                                                                                <a href="{{route('indicator.index')}}" class="nav-link">{{__('Indicator')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage appraisal')
                                                                            <li class="nav-item {{ (request()->is('appraisal*') ? 'active' : '')}}">
                                                                                <a href="{{route('appraisal.index')}}" class="nav-link">{{__('Appraisal')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage goal tracking')
                                                                            <li class="nav-item {{ (request()->is('goaltracking*') ? 'active' : '')}}">
                                                                                <a href="{{route('goaltracking.index')}}" class="nav-link">{{__('Goal Tracking')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                    </ul>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </ul>
                                                </div>
                                            </li>

                                            <li class="nav-item">
                                                <div class="collapse show" id="training-navbar" style="">
                                                    <ul class="nav flex-column">
                                                        <ul class="nav flex-column">
                                                            <li class="nav-item submenu-li ">
                                                                <a class="nav-link {{ (Request::segment(1) == 'trainer' || Request::segment(1) == 'training') ? 'active' : 'collapsed'}}" href="#navbar-training" data-toggle="collapse" role="button" aria-expanded="{{ (Request::segment(1) == 'trainer' || Request::segment(1) == 'training') ? 'true' : 'false'}}" aria-controls="navbar-getting-started1">
                                                                    {{__('Training')}}
                                                                    <i class="fas fa-sort-up"></i>
                                                                </a>
                                                                <div class="submenu-ul {{ (Request::segment(1) == 'trainer' || Request::segment(1) == 'training') ? 'show' : 'collapse'}}" id="navbar-training" style="">
                                                                    <ul class="nav flex-column">
                                                                        @can('manage training')
                                                                            <li class="nav-item {{ (request()->is('training*') ? 'active' : '')}}">
                                                                                <a href="{{route('training.index')}}" class="nav-link">{{__('Training List')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage trainer')
                                                                            <li class="nav-item {{ (request()->is('trainer*') ? 'active' : '')}}">
                                                                                <a href="{{route('trainer.index')}}" class="nav-link">{{__('Trainer')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                    </ul>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="nav-item">
                                                <div class="collapse show" id="recruitment-navbar" style="">
                                                    <ul class="nav flex-column">
                                                        <ul class="nav flex-column">
                                                            <li class="nav-item submenu-li">
                                                                <a class="nav-link {{ (Request::segment(1) == 'job' || Request::segment(1) == 'job-application' || Request::segment(1) == 'candidates-job-applications' || Request::segment(1) == 'job-onboard' || Request::segment(1) == 'custom-question' || Request::segment(1) == 'interview-schedule' || Request::segment(1) == 'career') ? 'active' : 'collapsed'}}" href="#navbar-recruitment" data-toggle="collapse" role="button"
                                                                   aria-expanded="{{ (Request::segment(1) == 'job' || Request::segment(1) == 'job-application' || Request::segment(1) == 'candidates-job-applications' || Request::segment(1) == 'job-onboard' || Request::segment(1) == 'custom-question' || Request::segment(1) == 'interview-schedule' || Request::segment(1) == 'career') ? 'true' : 'false'}}" aria-controls="navbar-getting-started1">
                                                                    {{__('Recruitment')}}
                                                                    <i class="fas fa-sort-up"></i>
                                                                </a>
                                                                <div class="submenu-ul {{ (Request::segment(1) == 'job' || Request::segment(1) == 'job-application' || Request::segment(1) == 'candidates-job-applications' || Request::segment(1) == 'job-onboard' || Request::segment(1) == 'custom-question' || Request::segment(1) == 'interview-schedule' || Request::segment(1) == 'career') ? 'show' : 'collapse'}}" id="navbar-recruitment" style="">
                                                                    <ul class="nav flex-column">
                                                                        @can('manage job')
                                                                            <li class="nav-item {{ (Request::route()->getName() == 'job.index' || Request::route()->getName() == 'job.create' ? 'active' : '')}}">
                                                                                <a href="{{route('job.index')}}" class="nav-link">{{__('Jobs')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage job application')
                                                                            <li class="nav-item {{ (request()->is('job-application*') ? 'active' : '')}}">
                                                                                <a href="{{route('job-application.index')}}" class="nav-link">{{__('Job Application')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage job application')
                                                                            <li class="nav-item {{ (request()->is('candidates-job-applications') ? 'active' : '')}}">
                                                                                <a href="{{route('job.application.candidate')}}" class="nav-link">{{__('Job Candidate')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage job application')
                                                                            <li class="nav-item {{ (request()->is('job-onboard*') ? 'active' : '')}}">
                                                                                <a href="{{route('job.on.board')}}" class="nav-link">{{__('Job OnBoard')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage custom question')
                                                                            <li class="nav-item {{ (request()->is('custom-question*') ? 'active' : '')}}">
                                                                                <a href="{{route('custom-question.index')}}" class="nav-link">{{__('Custom Question')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('show interview schedule')
                                                                            <li class="nav-item {{ (request()->is('interview-schedule*') ? 'active' : '')}}">
                                                                                <a href="{{route('interview-schedule.index')}}" class="nav-link">{{__('Interview Schedule')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('show career')
                                                                            <li class="nav-item {{ (request()->is('career*') ? 'active' : '')}}">
                                                                                <a href="{{route('career',[2,'en'])}}" target="_blank" class="nav-link">{{__('Career')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                    </ul>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </ul>
                                                </div>
                                            </li>
                                            <li class="nav-item">
                                                <div class="collapse show" id="hrm-hr-navbar" style="">
                                                    <ul class="nav flex-column">
                                                        <li class="nav-item submenu-li ">
                                                            <a class="nav-link {{ (Request::segment(1) == 'holiday-calender' || Request::segment(1) == 'holiday' || Request::segment(1) == 'policies' || Request::segment(1) == 'award' || Request::segment(1) == 'transfer' || Request::segment(1) == 'resignation' || Request::segment(1) == 'travel' || Request::segment(1) == 'promotion' || Request::segment(1) == 'complaint' || Request::segment(1) == 'warning' || Request::segment(1) == 'termination' || Request::segment(1) == 'announcement') ? 'active' : 'collapsed'}}"
                                                               href="#hrm-hr" data-toggle="collapse" role="button"
                                                               aria-expanded="{{(Request::segment(1) == 'holiday-calender' || Request::segment(1) == 'holiday' || Request::segment(1) == 'award' || Request::segment(1) == 'transfer' || Request::segment(1) == 'resignation' || Request::segment(1) == 'travel' || Request::segment(1) == 'promotion' || Request::segment(1) == 'complaint' || Request::segment(1) == 'warning' || Request::segment(1) == 'termination' || Request::segment(1) == 'announcement' || Request::segment(1) == 'policies') ? 'true' : 'false'}}"
                                                               aria-controls="user">
                                                                {{__('HR')}}
                                                                <i class="fas fa-sort-up"></i>
                                                            </a>
                                                            <div
                                                                class="submenu-ul collapse{{ (Request::segment(1) == 'holiday-calender' || Request::segment(1) == 'holiday' || Request::segment(1) == 'award' || Request::segment(1) == 'transfer' || Request::segment(1) == 'resignation' || Request::segment(1) == 'travel' || Request::segment(1) == 'promotion' || Request::segment(1) == 'complaint' || Request::segment(1) == 'warning' || Request::segment(1) == 'termination' || Request::segment(1) == 'announcement' || Request::segment(1) == 'policies') ? 'show' : ''}}"
                                                                id="hrm-hr" style="">
                                                                <ul class="nav flex-column">
                                                                    @can('manage award')
                                                                        <li class="nav-item {{ (request()->is('award*') ? 'active' : '')}}">
                                                                            <a href="{{route('award.index')}}" class="nav-link">{{__('Award')}}</a>
                                                                        </li>
                                                                    @endcan
                                                                    @can('manage transfer')
                                                                        <li class="nav-item {{ (request()->is('transfer*') ? 'active' : '')}}">
                                                                            <a href="{{route('transfer.index')}}" class="nav-link">{{__('Transfer')}}</a>
                                                                        </li>
                                                                    @endcan
                                                                    @can('manage resignation')
                                                                        <li class="nav-item {{ (request()->is('resignation*') ? 'active' : '')}}">
                                                                            <a href="{{route('resignation.index')}}" class="nav-link">{{__('Resignation')}}</a>
                                                                        </li>
                                                                    @endcan
                                                                    @can('manage travel')
                                                                        <li class="nav-item {{ (request()->is('travel*') ? 'active' : '')}}">
                                                                            <a href="{{route('travel.index')}}" class="nav-link">{{__('Trip')}}</a>
                                                                        </li>
                                                                    @endcan
                                                                    @can('manage promotion')
                                                                        <li class="nav-item {{ (request()->is('promotion*') ? 'active' : '')}}">
                                                                            <a href="{{route('promotion.index')}}" class="nav-link">{{__('Promotion')}}</a>
                                                                        </li>
                                                                    @endcan
                                                                    @can('manage complaint')
                                                                        <li class="nav-item {{ (request()->is('complaint*') ? 'active' : '')}}">
                                                                            <a href="{{route('complaint.index')}}" class="nav-link">{{__('Complaints')}}</a>
                                                                        </li>
                                                                    @endcan
                                                                    @can('manage warning')
                                                                        <li class="nav-item {{ (request()->is('warning*') ? 'active' : '')}}">
                                                                            <a href="{{route('warning.index')}}" class="nav-link">{{__('Warning')}}</a>
                                                                        </li>
                                                                    @endcan
                                                                    @can('manage termination')
                                                                        <li class="nav-item {{ (request()->is('termination*') ? 'active' : '')}}">
                                                                            <a href="{{route('termination.index')}}" class="nav-link">{{__('Termination')}}</a>
                                                                        </li>
                                                                    @endcan
                                                                    @can('manage announcement')
                                                                        <li class="nav-item {{ (request()->is('announcement*') ? 'active' : '')}}">
                                                                            <a href="{{route('announcement.index')}}" class="nav-link">{{__('Announcement')}}</a>
                                                                        </li>
                                                                    @endcan
                                                                    @can('manage holiday')
                                                                        <li class="nav-item {{ (request()->is('holiday*') || request()->is('holiday-calender') ? 'active' : '')}}">
                                                                            <a href="{{route('holiday.index')}}" class="nav-link">{{__('Holidays')}}</a>
                                                                        </li>
                                                                    @endcan


                                                                </ul>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </li>
                                            @can('manage event')
                                                <li class="nav-item {{ (request()->is('event*') ? 'active' : '')}}">
                                                    <a href="{{route('event.index')}}" class="nav-link">{{__('Event')}}</a>
                                                </li>
                                            @endcan
                                            @can('manage meeting')
                                                <li class="nav-item {{ (request()->is('meeting*') ? 'active' : '')}}">
                                                    <a href="{{route('meeting.index')}}" class="nav-link">{{__('Meeting')}}</a>
                                                </li>
                                            @endcan
                                            @can('manage assets')
                                                <li class="nav-item {{ (request()->is('account-assets*') ? 'active' : '')}}">
                                                    <a href="{{route('account-assets.index')}}" class="nav-link">{{__('Asset')}}</a>
                                                </li>
                                            @endcan
                                            @can('manage document')
                                                <li class="nav-item {{ (request()->is('document-upload*') ? 'active' : '')}}">
                                                    <a href="{{route('document-upload.index')}}" class="nav-link">{{__('Document')}}</a>
                                                </li>
                                            @endcan
                                            @can('manage company policy')
                                                <li class="nav-item {{ (request()->is('company-policy*') ? 'active' : '')}}">
                                                    <a href="{{route('company-policy.index')}}" class="nav-link">{{__('Company policy')}}</a>
                                                </li>
                                            @endcan
                                            @can('manage report')
                                                <li class="nav-item">
                                                    <div class="collapse show" id="hr-report-navbar" style="">
                                                        <ul class="nav flex-column">
                                                            <ul class="nav flex-column">
                                                                <li class="nav-item submenu-li ">
                                                                    <a class="nav-link {{ (Request::segment(1) == 'reports-monthly-attendance' || Request::segment(1) == 'reports-leave' || Request::segment(1) == 'reports-payroll') ? 'active' : 'collapsed'}}" href="#hr-report" data-toggle="collapse" role="button" aria-expanded="{{(Request::segment(1) == 'reports-monthly-attendance' || Request::segment(1) == 'reports-leave' || Request::segment(1) == 'reports-payroll') ? 'true' : 'false'}}"
                                                                       aria-controls="user">
                                                                        {{__('Report')}}
                                                                        <i class="fas fa-sort-up"></i>
                                                                    </a>
                                                                    <div class="submenu-ul collapse{{ (Request::segment(1) == 'reports-monthly-attendance' || Request::segment(1) == 'reports-leave' || Request::segment(1) == 'reports-payroll') ? 'show' : ''}}" id="hr-report" style="">
                                                                        <ul class="nav flex-column">
                                                                            <li class="nav-item {{ request()->is('reports-monthly-attendance') ? 'active' : '' }}">
                                                                                <a class="nav-link" href="{{ route('report.monthly.attendance') }}">{{ __('Monthly Attendance') }}</a>
                                                                            </li>
                                                                            <li class="nav-item {{ request()->is('reports-leave') ? 'active' : '' }}">
                                                                                <a class="nav-link" href="{{ route('report.leave') }}">{{ __('Leave') }}</a>
                                                                            </li>
                                                                            <li class="nav-item {{ request()->is('reports-payroll') ? 'active' : '' }}">
                                                                                <a class="nav-link" href="{{ route('report.payroll') }}">{{ __('Payroll') }}</a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endcan
                                            <li class="nav-item">
                                                <div class="collapse show" id="hrmgo_constants" style="">
                                                    <ul class="nav flex-column">
                                                        <ul class="nav flex-column">
                                                            <li class="nav-item submenu-li">
                                                                <a class="nav-link {{ (Request::segment(1) == 'leavetype' || Request::segment(1) == 'document' || Request::segment(1) == 'branch' || Request::segment(1) == 'department' || Request::segment(1) == 'designation' || Request::segment(1) == 'job-stage' || Request::segment(1) == 'job-category' || Request::segment(1) == 'terminationtype' || Request::segment(1) == 'awardtype' || Request::segment(1) == 'trainingtype' || Request::segment(1) == 'goaltype' || Request::segment(1) == 'paysliptype' || Request::segment(1) == 'allowanceoption' || Request::segment(1) == 'loanoption' || Request::segment(1) == 'deductionoption') ? 'active' : 'collapsed'}}"
                                                                   href="#hrmgo_navbar_constants" data-toggle="collapse" role="button"
                                                                   aria-expanded="{{ (Request::segment(1) == 'leavetype' || Request::segment(1) == 'document' || Request::segment(1) == 'branch' || Request::segment(1) == 'department' || Request::segment(1) == 'designation' || Request::segment(1) == 'job-stage' || Request::segment(1) == 'job-category' || Request::segment(1) == 'terminationtype' || Request::segment(1) == 'awardtype' || Request::segment(1) == 'trainingtype' || Request::segment(1) == 'goaltype' || Request::segment(1) == 'paysliptype' || Request::segment(1) == 'allowanceoption' || Request::segment(1) == 'loanoption' || Request::segment(1) == 'deductionoption') ? 'true' : 'false'}}"
                                                                   aria-controls="navbar-getting-started1">
                                                                    {{__('Setup')}}
                                                                    <i class="fas fa-sort-up"></i>
                                                                </a>
                                                                <div
                                                                    class="submenu-ul {{ (Request::segment(1) == 'leavetype' || Request::segment(1) == 'document' || Request::segment(1) == 'branch' || Request::segment(1) == 'department' || Request::segment(1) == 'designation' || Request::segment(1) == 'job-stage' || Request::segment(1) == 'job-category' || Request::segment(1) == 'terminationtype' || Request::segment(1) == 'awardtype' || Request::segment(1) == 'trainingtype' || Request::segment(1) == 'goaltype' || Request::segment(1) == 'paysliptype' || Request::segment(1) == 'allowanceoption' || Request::segment(1) == 'loanoption' || Request::segment(1) == 'deductionoption') ? 'show' : 'collapse'}}"
                                                                    id="hrmgo_navbar_constants" style="">
                                                                    <ul class="nav flex-column">
                                                                        @can('manage branch')
                                                                            <li class="nav-item {{ (request()->is('branch*') ? 'active' : '')}}">
                                                                                <a href="{{route('branch.index')}}" class="nav-link">{{__('Branch')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage department')
                                                                            <li class="nav-item {{ (request()->is('department*') ? 'active' : '')}}">
                                                                                <a href="{{route('department.index')}}" class="nav-link">{{__('Department')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage designation')
                                                                            <li class="nav-item {{ (request()->is('designation*') ? 'active' : '')}}">
                                                                                <a href="{{route('designation.index')}}" class="nav-link">{{__('Designation')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage leave type')
                                                                            <li class="nav-item {{ (Request::route()->getName() == 'leavetype.index' ? 'active' : '')}}">
                                                                                <a href="{{route('leavetype.index')}}" class="nav-link">{{__('Leave Type')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage document type')
                                                                            <li class="nav-item {{ (Request::route()->getName() == 'document.index' ? 'active' : '')}}">
                                                                                <a href="{{route('document.index')}}" class="nav-link">{{__('Document Type')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage payslip type')
                                                                            <li class="nav-item {{ (request()->is('paysliptype*') ? 'active' : '')}}">
                                                                                <a href="{{route('paysliptype.index')}}" class="nav-link">{{__('Payslip Type')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage allowance option')
                                                                            <li class="nav-item {{ (request()->is('allowanceoption*') ? 'active' : '')}}">
                                                                                <a href="{{route('allowanceoption.index')}}" class="nav-link">{{__('Allowance Option')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage loan option')
                                                                            <li class="nav-item {{ (request()->is('loanoption*') ? 'active' : '')}}">
                                                                                <a href="{{route('loanoption.index')}}" class="nav-link">{{__('Loan Option')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage deduction option')
                                                                            <li class="nav-item {{ (request()->is('deductionoption*') ? 'active' : '')}}">
                                                                                <a href="{{route('deductionoption.index')}}" class="nav-link">{{__('Deduction Option')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage goal type')
                                                                            <li class="nav-item {{ (request()->is('goaltype*') ? 'active' : '')}}">
                                                                                <a href="{{route('goaltype.index')}}" class="nav-link">{{__('Goal Type')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage training type')
                                                                            <li class="nav-item {{ (request()->is('trainingtype*') ? 'active' : '')}}">
                                                                                <a href="{{route('trainingtype.index')}}" class="nav-link">{{__('Training Type')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage award type')
                                                                            <li class="nav-item {{ (request()->is('awardtype*') ? 'active' : '')}}">
                                                                                <a href="{{route('awardtype.index')}}" class="nav-link">{{__('Award Type')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage termination type')
                                                                            <li class="nav-item {{ (request()->is('terminationtype*') ? 'active' : '')}}">
                                                                                <a href="{{route('terminationtype.index')}}" class="nav-link">{{__('Termination Type')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage job category')
                                                                            <li class="nav-item {{ (request()->is('job-category*') ? 'active' : '')}}">
                                                                                <a href="{{route('job-category.index')}}" class="nav-link">{{__('Job Category')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                        @can('manage job stage')
                                                                            <li class="nav-item {{ (request()->is('job-stage*') ? 'active' : '')}}">
                                                                                <a href="{{route('job-stage.index')}}" class="nav-link">{{__('Job Stage')}}</a>
                                                                            </li>
                                                                        @endcan
                                                                            @can('Manage Competencies')
                                                                                <li class="nav-item {{ request()->is('competencies*') ? 'active' : '' }}">
                                                                                    <a class="nav-link" href="{{ route('competencies.index') }}">{{ __('Competencies') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                    </ul>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </ul>
                                                </div>
                                            </li>


                                        </ul>
                                    </div>
                                </li>
                            @endcan
                        @endif
                    <!-- end Hrm -->
                        @if(\Auth::user()->show_account() == 1)
                            @if( Gate::check('manage customer') || Gate::check('manage vender'))
                                <li class="nav-item">
                                    <a class="nav-link {{ (Request::segment(1) == 'print-setting' || Request::segment(1) == 'customer' || Request::segment(1) == 'vender' || Request::segment(1) == 'proposal' || Request::segment(1) == 'bank-account' || Request::segment(1) == 'bank-transfer' || Request::segment(1) == 'invoice' || Request::segment(1) == 'revenue' || Request::segment(1) == 'credit-note' || Request::segment(1) == 'taxes' || Request::segment(1) == 'product-category' || Request::segment(1) == 'product-unit' || Request::segment(1) == 'payment-method' || Request::segment(1) == 'custom-field' || Request::segment(1) == 'chart-of-account-type' || (Request::segment(1) == 'report' || Request::segment(1) == 'transaction') &&  Request::segment(2) != 'ledger' &&  Request::segment(2) != 'balance-sheet' &&  Request::segment(2) != 'trial-balance' || Request::segment(1) == 'goal' || Request::segment(1) == 'chart-of-account' || Request::segment(1) == 'journal-entry' || Request::segment(2) == 'ledger' ||  Request::segment(2) == 'balance-sheet' ||  Request::segment(2) == 'trial-balance' || Request::segment(1) == 'bill' || Request::segment(1) == 'payment' || Request::segment(1) == 'debit-note')?' active':'collapsed'}}"
                                       href="#navbar-account" data-toggle="collapse" role="button"
                                       aria-expanded="{{ (Request::segment(1) == 'customer' || Request::segment(1) == 'vender' || Request::segment(1) == 'proposal' || Request::segment(1) == 'bank-account' || Request::segment(1) == 'bank-transfer' || Request::segment(1) == 'invoice' || Request::segment(1) == 'revenue' || Request::segment(1) == 'credit-note' || Request::segment(1) == 'taxes' || Request::segment(1) == 'product-category' || Request::segment(1) == 'product-unit' || Request::segment(1) == 'payment-method' || Request::segment(1) == 'custom-field' || Request::segment(1) == 'chart-of-account-type' || (Request::segment(1) == 'report' || Request::segment(1) == 'transaction') &&  Request::segment(2) != 'ledger' &&  Request::segment(2) != 'balance-sheet' &&  Request::segment(2) != 'trial-balance' || Request::segment(1) == 'goal' || Request::segment(1) == 'chart-of-account' || Request::segment(1) == 'journal-entry' || Request::segment(2) == 'ledger' ||  Request::segment(2) == 'balance-sheet' ||  Request::segment(2) == 'trial-balance' || Request::segment(1) == 'bill' || Request::segment(1) == 'payment' || Request::segment(1) == 'debit-note' || Request::segment(1) == 'print-setting')?'true':'false'}}"
                                       aria-controls="navbar-account">
                                        <i class="fa fa-briefcase"></i>{{__('Account')}}
                                        <i class="fas fa-sort-up"></i>
                                    </a>
                                    <div
                                        class="collapse {{ (Request::segment(1) == 'print-setting' || Request::segment(1) == 'customer' || Request::segment(1) == 'vender' || Request::segment(1) == 'proposal' || Request::segment(1) == 'bank-account' || Request::segment(1) == 'bank-transfer' || Request::segment(1) == 'invoice' || Request::segment(1) == 'revenue' || Request::segment(1) == 'credit-note' || Request::segment(1) == 'taxes' || Request::segment(1) == 'product-category' || Request::segment(1) == 'product-unit' || Request::segment(1) == 'payment-method' || Request::segment(1) == 'custom-field' || Request::segment(1) == 'chart-of-account-type' || (Request::segment(1) == 'report' || Request::segment(1) == 'transaction') &&  Request::segment(2) != 'ledger' &&  Request::segment(2) != 'balance-sheet' &&  Request::segment(2) != 'trial-balance' || Request::segment(1) == 'goal' || Request::segment(1) == 'chart-of-account' || Request::segment(1) == 'journal-entry' || Request::segment(2) == 'ledger' ||  Request::segment(2) == 'balance-sheet' ||  Request::segment(2) == 'trial-balance' || Request::segment(1) == 'bill' || Request::segment(1) == 'payment' || Request::segment(1) == 'debit-note')?'show':''}}"
                                        id="navbar-account">
                                        <ul class="nav flex-column">
                                            @if(Gate::check('manage customer'))
                                                <li class="nav-item {{ (Request::segment(1) == 'customer')?'active':''}}">
                                                    <a href="{{ route('customer.index') }}" class="nav-link">
                                                        <!-- <i class="fas fa-user-ninja"></i> -->
                                                        {{__('Customer')}}
                                                    </a>
                                                </li>
                                            @endif
                                            @if(Gate::check('manage vender'))
                                                <li class="nav-item {{ (Request::segment(1) == 'vender')?'active':''}}">
                                                    <a href="{{ route('vender.index') }}" class="nav-link">
                                                        <!-- <i class="fas fa-sticky-note"></i> -->
                                                        {{__('Vendor')}}
                                                    </a>
                                                </li>
                                            @endif
                                            @if(Gate::check('manage proposal'))
                                                <li class="nav-item {{ (Request::segment(1) == 'proposal')?'active':''}}">
                                                    <a href="{{ route('proposal.index') }}" class="nav-link">
                                                        <!-- <i class="fas fa-receipt"></i> -->
                                                        {{__('Proposal')}}
                                                    </a>
                                                </li>
                                            @endif
                                            @if( Gate::check('manage bank account') ||  Gate::check('manage bank transfer'))
                                                <li class="nav-item">
                                                    <div class="collapse show" id="banking-navbar" style="">
                                                        <ul class="nav flex-column">
                                                            <ul class="nav flex-column">
                                                                <li class="nav-item submenu-li ">
                                                                    <a class="nav-link {{(Request::segment(1) == 'bank-account' || Request::segment(1) == 'bank-transfer')? 'active' :'collapsed'}}" href="#banking-nav" data-toggle="collapse" role="button" aria-expanded="{{(Request::segment(1) == 'bank-account' || Request::segment(1) == 'transfer')? 'true' :'false'}}" aria-controls="navbar-getting-started1">
                                                                        {{__('Banking')}}
                                                                        <i class="fas fa-sort-up"></i>
                                                                    </a>
                                                                    <div class="submenu-ul {{(Request::segment(1) == 'bank-account' || Request::segment(1) == 'bank-transfer')? 'show' :'collapse'}}" id="banking-nav" style="">
                                                                        <ul class="nav flex-column">
                                                                            @can('manage bank account')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'bank-account.index' || Request::route()->getName() == 'bank-account.create' || Request::route()->getName() == 'bank-account.edit') ? ' active' : '' }}">
                                                                                    <a href="{{ route('bank-account.index') }}" class="nav-link">{{ __('Account') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('manage bank transfer')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'bank-transfer.index' || Request::route()->getName() == 'bank-transfer.create' || Request::route()->getName() == 'bank-transfer.edit') ? ' active' : '' }}">
                                                                                    <a href="{{route('bank-transfer.index')}}" class="nav-link">{{ __('Transfer') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                        </ul>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endif
                                            @if( Gate::check('manage invoice') ||  Gate::check('manage revenue') ||  Gate::check('manage credit note'))
                                                <li class="nav-item">
                                                    <div class="collapse show" id="income-navbar" style="">
                                                        <ul class="nav flex-column">
                                                            <ul class="nav flex-column">
                                                                <li class="nav-item submenu-li ">
                                                                    <a class="nav-link {{(Request::segment(1) == 'invoice' || Request::segment(1) == 'revenue' || Request::segment(1) == 'credit-note')? 'active' :'collapsed'}}" href="#income-nav" data-toggle="collapse" role="button" aria-expanded="{{(Request::segment(1) == 'invoice' || Request::segment(1) == 'revenue' || Request::segment(1) == 'credit-note')? 'true' :'false'}}" aria-controls="navbar-getting-started1">
                                                                        {{__('Income')}}
                                                                        <i class="fas fa-sort-up"></i>
                                                                    </a>
                                                                    <div class="submenu-ul {{(Request::segment(1) == 'invoice' || Request::segment(1) == 'revenue' || Request::segment(1) == 'credit-note')? 'show' :'collapse'}}" id="income-nav" style="">
                                                                        <ul class="nav flex-column">
                                                                            @can('manage invoice')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'invoice.index' || Request::route()->getName() == 'invoice.create' || Request::route()->getName() == 'invoice.edit' || Request::route()->getName() == 'invoice.show') ? ' active' : '' }}">
                                                                                    <a href="{{ route('invoice.index') }}" class="nav-link">{{ __('Invoice') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('manage revenue')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'revenue.index' || Request::route()->getName() == 'revenue.create' || Request::route()->getName() == 'revenue.edit') ? ' active' : '' }}">
                                                                                    <a href="{{route('revenue.index')}}" class="nav-link">{{ __('Revenue') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('manage credit note')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'credit.note' ) ? ' active' : '' }}">
                                                                                    <a href="{{route('credit.note')}}" class="nav-link">{{ __('Credit Note') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                        </ul>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endif
                                            @if( Gate::check('manage bill')  ||  Gate::check('manage payment') ||  Gate::check('manage debit note'))
                                                <li class="nav-item">
                                                    <div class="collapse show" id="expense-navbar" style="">
                                                        <ul class="nav flex-column">
                                                            <ul class="nav flex-column">
                                                                <li class="nav-item submenu-li ">
                                                                    <a class="nav-link {{(Request::segment(1) == 'bill' || Request::segment(1) == 'payment' || Request::segment(1) == 'debit-note')? 'active' :'collapsed'}}" href="#expense-nav" data-toggle="collapse" role="button" aria-expanded="{{(Request::segment(1) == 'bill' || Request::segment(1) == 'payment' || Request::segment(1) == 'debit-note')? 'true' :'false'}}" aria-controls="navbar-getting-started1">
                                                                        {{__('Expense')}}
                                                                        <i class="fas fa-sort-up"></i>
                                                                    </a>
                                                                    <div class="submenu-ul {{(Request::segment(1) == 'bill' || Request::segment(1) == 'payment' || Request::segment(1) == 'debit-note')? 'show' :'collapse'}}" id="expense-nav" style="">
                                                                        <ul class="nav flex-column">
                                                                            @can('manage bill')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'bill.index' || Request::route()->getName() == 'bill.create' || Request::route()->getName() == 'bill.edit' || Request::route()->getName() == 'bill.show') ? ' active' : '' }}">
                                                                                    <a href="{{ route('bill.index') }}" class="nav-link">{{ __('Bill') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('manage payment')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'payment.index' || Request::route()->getName() == 'payment.create' || Request::route()->getName() == 'payment.edit') ? ' active' : '' }}">
                                                                                    <a href="{{route('payment.index')}}" class="nav-link">{{ __('Payment') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('manage debit note')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'debit.note' ) ? ' active' : '' }}">
                                                                                    <a href="{{route('debit.note')}}" class="nav-link">{{ __('Debit Note') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                        </ul>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endif
                                            @if( Gate::check('manage chart of account') ||  Gate::check('manage journal entry') ||   Gate::check('balance sheet report') ||  Gate::check('ledger report') ||  Gate::check('trial balance report'))
                                                <li class="nav-item">
                                                    <div class="collapse show" id="double-enrty-navbar" style="">
                                                        <ul class="nav flex-column">
                                                            <ul class="nav flex-column">
                                                                <li class="nav-item submenu-li ">
                                                                    <a class="nav-link {{(Request::segment(1) == 'chart-of-account' || Request::segment(1) == 'journal-entry' || Request::segment(2) == 'ledger' ||  Request::segment(2) == 'balance-sheet' ||  Request::segment(2) == 'trial-balance')? 'active' :'collapsed'}}" href="#double-enrty-nav" data-toggle="collapse" role="button"
                                                                       aria-expanded="{{(Request::segment(1) == 'chart-of-account' || Request::segment(1) == 'journal-entry' || Request::segment(2) == 'ledger' ||  Request::segment(2) == 'balance-sheet' ||  Request::segment(2) == 'trial-balance')? 'true' :'false'}}" aria-controls="navbar-getting-started1">
                                                                        {{__('Double Entry')}}
                                                                        <i class="fas fa-sort-up"></i>
                                                                    </a>
                                                                    <div class="submenu-ul {{(Request::segment(1) == 'chart-of-account' || Request::segment(1) == 'journal-entry' || Request::segment(2) == 'ledger' ||  Request::segment(2) == 'balance-sheet' ||  Request::segment(2) == 'trial-balance')? 'show' :'collapse'}}" id="double-enrty-nav" style="">
                                                                        <ul class="nav flex-column">
                                                                            @can('manage chart of account')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'chart-of-account.index') ? ' active' : '' }}">
                                                                                    <a href="{{ route('chart-of-account.index') }}" class="nav-link">{{ __('Chart of Accounts') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('manage journal entry')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'journal-entry.edit' || Request::route()->getName() == 'journal-entry.create' || Request::route()->getName() == 'journal-entry.index' || Request::route()->getName() == 'journal-entry.show') ? ' active' : '' }}">
                                                                                    <a href="{{ route('journal-entry.index') }}" class="nav-link">{{ __('Journal Account') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('ledger report')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'report.ledger' ) ? ' active' : '' }}">
                                                                                    <a href="{{route('report.ledger')}}" class="nav-link">{{ __('Ledger Summary') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('balance sheet report')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'report.balance.sheet' ) ? ' active' : '' }}">
                                                                                    <a href="{{route('report.balance.sheet')}}" class="nav-link">{{ __('Balance Sheet') }}</a>
                                                                                </li>
                                                                            @endcan

                                                                            @can('trial balance report')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'trial.balance' ) ? ' active' : '' }}">
                                                                                    <a href="{{route('trial.balance')}}" class="nav-link">{{ __('Trial Balance') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                        </ul>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endif
                                            @if(Gate::check('manage goal'))
                                                <li class="nav-item  {{ (Request::segment(1) == 'goal')?'active':''}}">
                                                    <a href="{{ route('goal.index') }}" class="nav-link">
                                                        <!-- <i class="fas fa-bullseye"></i> -->
                                                        {{__('Goal')}}
                                                    </a>
                                                </li>
                                            @endif
                                            @if( Gate::check('income report') || Gate::check('expense report') || Gate::check('income vs expense report') || Gate::check('tax report')  || Gate::check('loss & profit report') || Gate::check('invoice report') || Gate::check('bill report') || Gate::check('invoice report') ||  Gate::check('manage transaction')||  Gate::check('statement report'))
                                                <li class="nav-item">
                                                    <div class="collapse show" id="report-navbar" style="">
                                                        <ul class="nav flex-column">
                                                            <ul class="nav flex-column">
                                                                <li class="nav-item submenu-li ">
                                                                    <a class="nav-link {{((Request::segment(1) == 'report' || Request::segment(1) == 'transaction') &&  Request::segment(2) != 'ledger' &&  Request::segment(2) != 'balance-sheet' &&  Request::segment(2) != 'trial-balance')? 'active' :'collapsed'}}" href="#report-nav" data-toggle="collapse" role="button"
                                                                       aria-expanded="{{((Request::segment(1) == 'report' || Request::segment(1) == 'transaction') &&  Request::segment(2) != 'ledger' &&  Request::segment(2) != 'balance-sheet' &&  Request::segment(2) != 'trial-balance')? 'true' :'false'}}" aria-controls="navbar-getting-started1">
                                                                        {{__('Report')}}
                                                                        <i class="fas fa-sort-up"></i>
                                                                    </a>
                                                                    <div class="submenu-ul {{((Request::segment(1) == 'report' || Request::segment(1) == 'transaction') &&  Request::segment(2) != 'ledger' &&  Request::segment(2) != 'balance-sheet' &&  Request::segment(2) != 'trial-balance')? 'show' :'collapse'}}" id="report-nav" style="">
                                                                        <ul class="nav flex-column">
                                                                            @can('manage transaction')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'transaction.index' || Request::route()->getName() == 'transfer.create' || Request::route()->getName() == 'transaction.edit') ? ' active' : '' }}">
                                                                                    <a href="{{ route('transaction.index') }}" class="nav-link">{{ __('Transaction') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('statement report')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'report.account.statement') ? ' active' : '' }}">
                                                                                    <a href="{{route('report.account.statement')}}" class="nav-link">{{ __('Account Statement') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('income report')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'report.income.summary' ) ? ' active' : '' }}">
                                                                                    <a href="{{route('report.income.summary')}}" class="nav-link">{{ __('Income Summary') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('expense report')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'report.expense.summary' ) ? ' active' : '' }}">
                                                                                    <a href="{{route('report.expense.summary')}}" class="nav-link">{{ __('Expense Summary') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('income vs expense report')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'report.income.vs.expense.summary' ) ? ' active' : '' }}">
                                                                                    <a href="{{route('report.income.vs.expense.summary')}}" class="nav-link">{{ __('Income VS Expense') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('tax report')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'report.tax.summary' ) ? ' active' : '' }}">
                                                                                    <a href="{{route('report.tax.summary')}}" class="nav-link">{{ __('Tax Summary') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('loss & profit report')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'report.profit.loss.summary' ) ? ' active' : '' }}">
                                                                                    <a href="{{route('report.profit.loss.summary')}}" class="nav-link">{{ __('Profit & Loss') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('invoice report')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'report.invoice.summary' ) ? ' active' : '' }}">
                                                                                    <a href="{{route('report.invoice.summary')}}" class="nav-link">{{ __('Invoice Summary') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('bill report')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'report.bill.summary' ) ? ' active' : '' }}">
                                                                                    <a href="{{route('report.bill.summary')}}" class="nav-link">{{ __('Bill Summary') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                        </ul>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endif
                                            @if(Gate::check('manage constant tax') || Gate::check('manage constant category') ||Gate::check('manage constant unit') ||Gate::check('manage constant payment method') ||Gate::check('manage constant custom field') )
                                                <li class="nav-item">
                                                    <div class="collapse show" id="account-setup-navbar" style="">
                                                        <ul class="nav flex-column">
                                                            <ul class="nav flex-column">
                                                                <li class="nav-item submenu-li ">
                                                                    <a class="nav-link {{(Request::segment(1) == 'taxes' || Request::segment(1) == 'product-category' || Request::segment(1) == 'product-unit' || Request::segment(1) == 'payment-method' || Request::segment(1) == 'custom-field' || Request::segment(1) == 'chart-of-account-type')? 'active' :'collapsed'}}" href="#account-setup-nav" data-toggle="collapse" role="button"
                                                                       aria-expanded="{{(Request::segment(1) == 'taxes' || Request::segment(1) == 'product-category' || Request::segment(1) == 'product-unit' || Request::segment(1) == 'payment-method' || Request::segment(1) == 'custom-field' || Request::segment(1) == 'chart-of-account-type')? 'true' :'false'}}" aria-controls="navbar-getting-started1">
                                                                        {{__('Setup')}}
                                                                        <i class="fas fa-sort-up"></i>
                                                                    </a>
                                                                    <div class="submenu-ul {{(Request::segment(1) == 'taxes' || Request::segment(1) == 'product-category' || Request::segment(1) == 'product-unit' || Request::segment(1) == 'payment-method' || Request::segment(1) == 'custom-field' || Request::segment(1) == 'chart-of-account-type')? 'show' :'collapse'}}" id="account-setup-nav" style="">
                                                                        <ul class="nav flex-column">
                                                                            @can('manage constant tax')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'taxes.index' ) ? ' active' : '' }}">
                                                                                    <a href="{{ route('taxes.index') }}" class="nav-link">{{ __('Taxes') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('manage constant category')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'product-category.index' ) ? 'active' : '' }}">
                                                                                    <a href="{{route('product-category.index')}}" class="nav-link">{{ __('Category') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('manage constant unit')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'product-unit.index' ) ? ' active' : '' }}">
                                                                                    <a href="{{route('product-unit.index')}}" class="nav-link">{{ __('Unit') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                            @can('manage constant custom field')
                                                                                <li class="nav-item {{ (Request::route()->getName() == 'custom-field.index' ) ? 'active' : '' }}">
                                                                                    <a href="{{route('custom-field.index')}}" class="nav-link">{{ __('Custom Field') }}</a>
                                                                                </li>
                                                                            @endcan
                                                                        </ul>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </ul>
                                                    </div>
                                                </li>
                                            @endif
                                            @if(Gate::check('manage print settings'))
                                                <li class="nav-item {{ (Request::segment(1) == 'print-setting') ? ' active' : '' }}">
                                                    <a href="{{ route('print.setting') }}" class="nav-link">
                                                        <!-- <i class="fas fa-sliders-h"></i> -->
                                                        {{__('Print Settings')}}
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
                                </li>
                            @endif
                        @endif
                    @endif
                    @if(\Auth::user()->type!='super admin')
                        <li class="nav-item">
                            <a href="{{route('support.index')}}" class="nav-link {{ (Request::segment(1) == 'support')?'active':''}}">
                                <i class="fas fa-ticket-alt"></i>{{__('Support')}}
                            </a>
                        </li>
                    @endif
                    @if(\Auth::user()->type!='super admin' && \Auth::user()->type!='client')
                        <li class="nav-item">
                            <a href="{{ url('messages') }}" class="nav-link {{ (Request::segment(1) == 'messages')?'active':''}}">
                                <i class="fab fa-facebook-messenger"></i>{{__('Messenger')}}
                            </a>
                        </li>
                    @endif

                    @if(Gate::check('manage company plan'))
                        <li class="nav-item">
                            <a href="{{ route('plans.index') }}" class="nav-link {{ (Request::route()->getName() == 'plans.index' || Request::route()->getName() == 'stripe') ? ' active' : '' }}">
                                <i class="fas fa-trophy"></i>{{__('Plan')}}
                            </a>
                        </li>
                    @endif
                    @if(Gate::check('manage order') && Auth::user()->type == 'company')
                        <li class="nav-item">
                            <a href="{{ route('order.index') }}" class="nav-link {{ (Request::segment(1) == 'order')? 'active' : ''}}">
                                <i class="fas fa-cart-plus"></i>{{__('Order')}}
                            </a>
                        </li>
                    @endif
                    @if(Gate::check('manage company settings'))
                        <li class="nav-item">
                            <a href="{{ route('company.setting') }}" class="nav-link {{ (Request::segment(1) == 'company-setting') ? ' active' : '' }}">
                                <i class="fas fa-sliders-h"></i>
                                {{__('Settings')}}
                            </a>
                        </li>
                    @endif

                </ul>
            @endif
            @if((\Auth::user()->type == 'client'))
                <ul class="navbar-nav navbar-nav-docs">
                    @if(Gate::check('manage client dashboard'))
                        <li class="nav-item">
                            <a href="{{ route('client.dashboard.view') }}" class="nav-link {{ (Request::segment(1) == 'dashboard') ? ' active' : '' }}">
                                <i class="fas fa-fire"></i>
                                {{__('Dashboard')}}
                            </a>
                        </li>
                    @endif

                    @if(Gate::check('manage deal'))
                        <li class="nav-item">
                            <a href="{{ route('deals.index') }}" class="nav-link {{ (Request::segment(1) == 'deals') ? ' active' : '' }}">
                                <i class="fas fa-rocket"></i>
                                {{__('Deals')}}
                            </a>
                        </li>
                    @endif
                    @if(Gate::check('manage project'))
                        <li class="nav-item">
                            <a href="{{ route('projects.index') }}" class="nav-link {{ (Request::segment(1) == 'projects') ? ' active' : '' }}">
                                <i class="fa fa-project-diagram"></i>
                                {{__('Project')}}
                            </a>
                        </li>
                    @endif
                    @if(Gate::check('manage project task'))
                        <li class="nav-item">
                            <a href="{{ route('taskBoard.view', 'list') }}" class="nav-link {{ (Request::segment(1) == 'taskboard') ? ' active' : '' }}">
                                <i class="fas fa-tasks"></i>
                                {{__('Tasks')}}
                            </a>
                        </li>
                    @endif
                    @if(Gate::check('manage bug report'))
                        <li class="nav-item">
                            <a href="{{ route('bugs.view','list') }}" class="nav-link {{ (Request::segment(1) == 'bugs-report') ? ' active' : '' }}">
                                <i class="fas fa-bug"></i>
                                {{__('Bugs')}}
                            </a>
                        </li>
                    @endif
                    @if(Gate::check('manage timesheet'))
                        <li class="nav-item">
                            <a href="{{ route('timesheet.list') }}" class="nav-link {{ (Request::segment(1) == 'timesheet-list') ? ' active' : '' }}">
                                <i class="fas fa-clock"></i>
                                {{__('Timesheet')}}
                            </a>
                        </li>
                    @endif
                    @if(Gate::check('manage project task'))
                        <li class="nav-item">
                            <a href="{{ route('task.calendar',['all']) }}" class="nav-link {{ (Request::segment(1) == 'calendar') ? ' active' : '' }}">
                                <i class="fas fa-calendar"></i>
                                {{__('Task Calender')}}
                            </a>
                        </li>
                    @endif
                    <li class="nav-item">
                        <a href="{{route('support.index')}}" class="nav-link {{ (Request::segment(1) == 'support')?'active':''}}">
                            <i class="fas fa-ticket-alt"></i>{{__('Support')}}
                        </a>
                    </li>
                </ul>
            @endif
            @if((\Auth::user()->type == 'super admin'))
                <ul class="navbar-nav navbar-nav-docs">
                    @if(Gate::check('manage super admin dashboard'))
                        <li class="nav-item">
                            <a href="{{ route('client.dashboard.view') }}" class="nav-link {{ (Request::segment(1) == 'dashboard') ? ' active' : '' }}">
                                <i class="fas fa-fire"></i>
                                {{__('Dashboard')}}
                            </a>
                        </li>
                    @endif
                    @can('manage user')
                        <li class="nav-item">
                            <a href="{{ route('users.index') }}" class="nav-link {{ (Request::route()->getName() == 'users.index' || Request::route()->getName() == 'users.create' || Request::route()->getName() == 'users.edit') ? ' active' : '' }}">
                                <i class="fas fa-columns"></i>{{__('User') }}
                            </a>
                        </li>
                    @endcan

                    @if(Gate::check('manage plan'))
                        <li class="nav-item">
                            <a href="{{ route('plans.index') }}" class="nav-link {{ (Request::segment(1) == 'plans')?'active':''}}">
                                <i class="fas fa-trophy"></i>{{__('Plan')}}
                            </a>
                        </li>
                    @endif
                    @if(Gate::check('manage coupon'))
                        <li class="nav-item">
                            <a href="{{ route('coupons.index') }}" class="nav-link {{ (Request::segment(1) == 'coupons')?'active':''}}">
                                <i class="fas fa-gift"></i>{{__('Coupon')}}
                            </a>
                        </li>
                    @endif
                    @if(Gate::check('manage order'))
                        <li class="nav-item">
                            <a href="{{ route('order.index') }}" class="nav-link {{ (Request::segment(1) == 'orders')?'active':''}}">
                                <i class="fas fa-cart-plus"></i>{{__('Order')}}
                            </a>
                        </li>
                    @endif
                    @if(Auth::user()->type == 'super admin')
                        <li class="nav-item">
                            <a href="{{route('custom_landing_page.index')}}" class="nav-link">
                                <i class="fas fa-clipboard"></i>{{__('Landing page')}}
                            </a>
                        </li>
                    @endif
                    @if(Gate::check('manage system settings'))
                        <li class="nav-item">
                            <a href="{{ route('systems.index') }}" class="nav-link {{ (Request::route()->getName() == 'systems.index') ? ' active' : '' }}">
                                <i class="fas fa-sliders-h"></i>{{__('System Setting')}}
                            </a>
                        </li>
                    @endif
                </ul>
            @endif
        </div>
    </div>
</div>
