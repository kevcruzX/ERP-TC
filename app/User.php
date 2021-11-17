<?php

namespace App;

use Carbon\Carbon;
use Auth;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;


    protected $appends = ['profile'];

    protected $fillable = [
        'name',
        'email',
        'password',
        'type',
        'avatar',
        'lang',
        'delete_status',
        'plan',
        'plan_expire_date',
        'created_by',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public $settings;




      public function getProfileAttribute()
      {
          if(\Storage::exists($this->avatar) && !empty($this->avatar))
          {
              return $this->attributes['avatar'] =  asset(\Storage::url($this->avatar));
          }
          else
          {
              return $this->attributes['avatar'] =  asset(\Storage::url('avatar.png'));
          }
      }


    public function authId()
    {
        return $this->id;
    }

    public function creatorId()
    {
        if($this->type == 'company' || $this->type == 'super admin')
        {
            return $this->id;
        }
        else
        {
            return $this->created_by;
        }
    }

    public function ownerId()
    {
        if($this->type == 'company' || $this->type == 'super admin')
        {
            return $this->id;
        }
        else
        {
            return $this->created_by;
        }
    }
    public function ownerDetails()
    {

        if($this->type == 'company' || $this->type == 'super admin')
        {
            return User::where('id',$this->id)->first();
        }
        else
        {
            return User::where('id',$this->created_by)->first();
        }
    }

    public function currentLanguage()
    {
        return $this->lang;
    }

    public function priceFormat($price)
    {
        $settings = Utility::settings();

        return (($settings['site_currency_symbol_position'] == "pre") ? $settings['site_currency_symbol'] : '') . number_format($price, Utility::getValByName('decimal_number')) . (($settings['site_currency_symbol_position'] == "post") ? $settings['site_currency_symbol'] : '');
    }

    public function currencySymbol()
    {
        $settings = Utility::settings();

        return $settings['site_currency_symbol'];
    }

    public function dateFormat($date)
    {
        $settings = Utility::settings();

        return date($settings['site_date_format'], strtotime($date));
    }

    public function timeFormat($time)
    {
        $settings = Utility::settings();

        return date($settings['site_time_format'], strtotime($time));
    }

    public function invoiceNumberFormat($number)
    {
        $settings = Utility::settings();

        return $settings["invoice_prefix"] . sprintf("%05d", $number);
    }

    public function proposalNumberFormat($number)
    {
        $settings = Utility::settings();

        return $settings["proposal_prefix"] . sprintf("%05d", $number);
    }

    public function billNumberFormat($number)
    {
        $settings = Utility::settings();

        return $settings["bill_prefix"] . sprintf("%05d", $number);
    }

    public function journalNumberFormat($number)
    {
        $settings = Utility::settings();

        return $settings["journal_prefix"] . sprintf("%05d", $number);
    }


    public function getPlan()
    {
        return $this->hasOne('App\Plan', 'id', 'plan');
    }

    public function assignPlan($planID)
    {
        $plan = Plan::find($planID);
        if($plan)
        {
            $this->plan = $plan->id;
            if($plan->duration == 'month')
            {
                $this->plan_expire_date = Carbon::now()->addMonths(1)->isoFormat('YYYY-MM-DD');
            }
            elseif($plan->duration == 'year')
            {
                $this->plan_expire_date = Carbon::now()->addYears(1)->isoFormat('YYYY-MM-DD');
            }
            $this->save();

            $users     = User::where('created_by', '=', \Auth::user()->creatorId())->where('type', '!=', 'super admin')->where('type', '!=', 'company')->where('type','!=','client')->get();
            $clients = User::where('type','client')->get();
            $customers = Customer::where('created_by', '=', \Auth::user()->creatorId())->get();
            $venders   = Vender::where('created_by', '=', \Auth::user()->creatorId())->get();


            if($plan->max_users == -1)
            {
                foreach($users as $user)
                {
                    $user->is_active = 1;
                    $user->save();
                }
            }
            else
            {
                $userCount = 0;
                foreach($users as $user)
                {
                    $userCount++;
                    if($userCount <= $plan->max_users)
                    {
                        $user->is_active = 1;
                        $user->save();
                    }
                    else
                    {
                        $user->is_active = 0;
                        $user->save();
                    }
                }
            }


            if($plan->max_clients == -1)
            {
                foreach($clients as $client)
                {
                    $client->is_active = 1;
                    $client->save();
                }
            }
            else
            {
                $clientCount = 0;
                foreach($clients as $client)
                {
                    $clientCount++;
                    if($clientCount <= $plan->max_clients)
                    {
                        $client->is_active = 1;
                        $client->save();
                    }
                    else
                    {
                        $client->is_active = 0;
                        $client->save();
                    }
                }
            }

            if($plan->max_customers == -1)
            {
                foreach($customers as $customer)
                {
                    $customer->is_active = 1;
                    $customer->save();
                }
            }
            else
            {
                $customerCount = 0;
                foreach($customers as $customer)
                {
                    $customerCount++;
                    if($customerCount <= $plan->max_customers)
                    {
                        $customer->is_active = 1;
                        $customer->save();
                    }
                    else
                    {
                        $customer->is_active = 0;
                        $customer->save();
                    }
                }
            }


            if($plan->max_venders == -1)
            {
                foreach($venders as $vender)
                {
                    $vender->is_active = 1;
                    $vender->save();
                }
            }
            else
            {
                $venderCount = 0;
                foreach($venders as $vender)
                {
                    $venderCount++;
                    if($venderCount <= $plan->max_venders)
                    {
                        $vender->is_active = 1;
                        $vender->save();
                    }
                    else
                    {
                        $vender->is_active = 0;
                        $vender->save();
                    }
                }
            }

            return ['is_success' => true];
        }
        else
        {
            return [
                'is_success' => false,
                'error' => 'Plan is deleted.',
            ];
        }
    }

    public function customerNumberFormat($number)
    {
        $settings = Utility::settings();

        return $settings["customer_prefix"] . sprintf("%05d", $number);
    }

    public function venderNumberFormat($number)
    {
        $settings = Utility::settings();

        return $settings["vender_prefix"] . sprintf("%05d", $number);
    }

    public function countUsers()
    {
        return User::where('type', '!=', 'super admin')->where('type', '!=', 'company')->where('type','!=','client')->where('created_by', '=', $this->creatorId())->count();
    }

    public function countCompany()
    {
        return User::where('type', '=', 'company')->where('created_by', '=', $this->creatorId())->count();
    }

    public function countOrder()
    {
        return Order::count();
    }

    public function countplan()
    {
        return Plan::count();
    }

    public function countPaidCompany()
    {
        return User::where('type', '=', 'company')->whereNotIn(
            'plan', [
                      0,
                      1,
                  ]
        )->where('created_by', '=', \Auth::user()->id)->count();
    }

    public function countCustomers()
    {
        return Customer::where('created_by', '=', $this->creatorId())->count();
    }

    public function countVenders()
    {
        return Vender::where('created_by', '=', $this->creatorId())->count();
    }

    public function countInvoices()
    {
        return Invoice::where('created_by', '=', $this->creatorId())->count();
    }

    public function countBills()
    {
        return Bill::where('created_by', '=', $this->creatorId())->count();
    }

    public function todayIncome()
    {
        $revenue      = Revenue::where('created_by', '=', $this->creatorId())->whereRaw('Date(date) = CURDATE()')->where('created_by', \Auth::user()->creatorId())->sum('amount');
        $invoices     = Invoice:: select('*')->where('created_by', \Auth::user()->creatorId())->whereRAW('Date(send_date) = CURDATE()')->get();
        $invoiceArray = array();
        foreach($invoices as $invoice)
        {
            $invoiceArray[] = $invoice->getTotal();
        }
        $totalIncome = (!empty($revenue) ? $revenue : 0) + (!empty($invoiceArray) ? array_sum($invoiceArray) : 0);

        return $totalIncome;
    }

    public function todayExpense()
    {
        $payment = Payment::where('created_by', '=', $this->creatorId())->where('created_by', \Auth::user()->creatorId())->whereRaw('Date(date) = CURDATE()')->sum('amount');

        $bills = Bill:: select('*')->where('created_by', \Auth::user()->creatorId())->whereRAW('Date(send_date) = CURDATE()')->get();

        $billArray = array();
        foreach($bills as $bill)
        {
            $billArray[] = $bill->getTotal();
        }

        $totalExpense = (!empty($payment) ? $payment : 0) + (!empty($billArray) ? array_sum($billArray) : 0);

        return $totalExpense;
    }

    public function incomeCurrentMonth()
    {
        $currentMonth = date('m');
        $revenue      = Revenue::where('created_by', '=', $this->creatorId())->whereRaw('MONTH(date) = ?', [$currentMonth])->sum('amount');

        $invoices = Invoice:: select('*')->where('created_by', \Auth::user()->creatorId())->whereRAW('MONTH(send_date) = ?', [$currentMonth])->get();

        $invoiceArray = array();
        foreach($invoices as $invoice)
        {
            $invoiceArray[] = $invoice->getTotal();
        }
        $totalIncome = (!empty($revenue) ? $revenue : 0) + (!empty($invoiceArray) ? array_sum($invoiceArray) : 0);

        return $totalIncome;

    }

    public function expenseCurrentMonth()
    {
        $currentMonth = date('m');

        $payment = Payment::where('created_by', '=', $this->creatorId())->whereRaw('MONTH(date) = ?', [$currentMonth])->sum('amount');

        $bills     = Bill:: select('*')->where('created_by', \Auth::user()->creatorId())->whereRAW('MONTH(send_date) = ?', [$currentMonth])->get();
        $billArray = array();
        foreach($bills as $bill)
        {
            $billArray[] = $bill->getTotal();
        }

        $totalExpense = (!empty($payment) ? $payment : 0) + (!empty($billArray) ? array_sum($billArray) : 0);

        return $totalExpense;
    }

    public function getincExpBarChartData()
    {
        $month[]          = __('January');
        $month[]          = __('February');
        $month[]          = __('March');
        $month[]          = __('April');
        $month[]          = __('May');
        $month[]          = __('June');
        $month[]          = __('July');
        $month[]          = __('August');
        $month[]          = __('September');
        $month[]          = __('October');
        $month[]          = __('November');
        $month[]          = __('December');
        $dataArr['month'] = $month;


        for($i = 1; $i <= 12; $i++)
        {
            $monthlyIncome = Revenue::selectRaw('sum(amount) amount')->where('created_by', '=', $this->creatorId())->whereRaw('year(`date`) = ?', array(date('Y')))->whereRaw('month(`date`) = ?', $i)->first();
            $invoices      = Invoice:: select('*')->where('created_by', \Auth::user()->creatorId())->whereRaw('year(`send_date`) = ?', array(date('Y')))->whereRaw('month(`send_date`) = ?', $i)->get();

            $invoiceArray = array();
            foreach($invoices as $invoice)
            {
                $invoiceArray[] = $invoice->getTotal();
            }
            $totalIncome = (!empty($monthlyIncome) ? $monthlyIncome->amount : 0) + (!empty($invoiceArray) ? array_sum($invoiceArray) : 0);


            $incomeArr[] = !empty($totalIncome) ? number_format($totalIncome, 2) : 0;

            $monthlyExpense = Payment::selectRaw('sum(amount) amount')->where('created_by', '=', $this->creatorId())->whereRaw('year(`date`) = ?', array(date('Y')))->whereRaw('month(`date`) = ?', $i)->first();
            $bills          = Bill:: select('*')->where('created_by', \Auth::user()->creatorId())->whereRaw('year(`send_date`) = ?', array(date('Y')))->whereRaw('month(`send_date`) = ?', $i)->get();
            $billArray      = array();
            foreach($bills as $bill)
            {
                $billArray[] = $bill->getTotal();
            }

            $totalExpense = (!empty($monthlyExpense) ? $monthlyExpense->amount : 0) + (!empty($billArray) ? array_sum($billArray) : 0);

            $expenseArr[] = !empty($totalExpense) ? number_format($totalExpense, 2) : 0;
        }

        $dataArr['income']  = $incomeArr;
        $dataArr['expense'] = $expenseArr;

        return $dataArr;


    }

    public function getIncExpLineChartDate()
    {
        $usr           = \Auth::user();
        $m             = date("m");
        $de            = date("d");
        $y             = date("Y");
        $format        = 'Y-m-d';
        $arrDate       = [];
        $arrDateFormat = [];

        for($i = 0; $i <= 15 - 1; $i++)
        {
            $date = date($format, mktime(0, 0, 0, $m, ($de - $i), $y));

            $arrDay[]        = date('D', mktime(0, 0, 0, $m, ($de - $i), $y));
            $arrDate[]       = $date;
            $arrDateFormat[] = date("d-M", strtotime($date));;
        }
        $dataArr['day'] = $arrDateFormat;
        for($i = 0; $i < count($arrDate); $i++)
        {
            $dayIncome = Revenue::selectRaw('sum(amount) amount')->where('created_by', \Auth::user()->creatorId())->whereRaw('date = ?', $arrDate[$i])->first();

            $invoices     = Invoice:: select('*')->where('created_by', \Auth::user()->creatorId())->whereRAW('send_date = ?', $arrDate[$i])->get();
            $invoiceArray = array();
            foreach($invoices as $invoice)
            {
                $invoiceArray[] = $invoice->getTotal();
            }

            $incomeAmount = (!empty($dayIncome->amount) ? $dayIncome->amount : 0) + (!empty($invoiceArray) ? array_sum($invoiceArray) : 0);
            $incomeArr[]  = number_format($incomeAmount, 2);

            $dayExpense = Payment::selectRaw('sum(amount) amount')->where('created_by', \Auth::user()->creatorId())->whereRaw('date = ?', $arrDate[$i])->first();

            $bills     = Bill:: select('*')->where('created_by', \Auth::user()->creatorId())->whereRAW('send_date = ?', $arrDate[$i])->get();
            $billArray = array();
            foreach($bills as $bill)
            {
                $billArray[] = $bill->getTotal();
            }
            $expenseAmount = (!empty($dayExpense->amount) ? $dayExpense->amount : 0) + (!empty($billArray) ? array_sum($billArray) : 0);
            $expenseArr[]  = number_format($expenseAmount, 2);;
        }

        $dataArr['income']  = $incomeArr;
        $dataArr['expense'] = $expenseArr;

        return $dataArr;
    }

    public function totalCompanyUser($id)
    {
        return User::where('created_by', '=', $id)->count();
    }

    public function totalCompanyCustomer($id)
    {
        return Customer::where('created_by', '=', $id)->count();
    }

    public function totalCompanyVender($id)
    {
        return Vender::where('created_by', '=', $id)->count();
    }

    public function planPrice()
    {
        $user = \Auth::user();
        if($user->type == 'super admin')
        {
            $userId = $user->id;
        }
        else
        {
            $userId = $user->created_by;
        }

        return DB::table('settings')->where('created_by', '=', $userId)->get()->pluck('value', 'name');

    }

    public function currentPlan()
    {
        return $this->hasOne('App\Plan', 'id', 'plan');
    }

    public function weeklyInvoice()
    {
        $staticstart  = date('Y-m-d', strtotime('last Week'));
        $currentDate  = date('Y-m-d');
        $invoices     = Invoice:: select('*')->where('created_by', \Auth::user()->creatorId())->where('issue_date', '>=', $staticstart)->where('issue_date', '<=', $currentDate)->get();
        $invoiceTotal = 0;
        $invoicePaid  = 0;
        $invoiceDue   = 0;
        foreach($invoices as $invoice)
        {
            $invoiceTotal += $invoice->getTotal();
            $invoicePaid  += ($invoice->getTotal() - $invoice->getDue());
            $invoiceDue   += $invoice->getDue();
        }

        $invoiceDetail['invoiceTotal'] = $invoiceTotal;
        $invoiceDetail['invoicePaid']  = $invoicePaid;
        $invoiceDetail['invoiceDue']   = $invoiceDue;

        return $invoiceDetail;
    }

    public function monthlyInvoice()
    {
        $staticstart  = date('Y-m-d', strtotime('last Month'));
        $currentDate  = date('Y-m-d');
        $invoices     = Invoice:: select('*')->where('created_by', \Auth::user()->creatorId())->where('issue_date', '>=', $staticstart)->where('issue_date', '<=', $currentDate)->get();
        $invoiceTotal = 0;
        $invoicePaid  = 0;
        $invoiceDue   = 0;
        foreach($invoices as $invoice)
        {
            $invoiceTotal += $invoice->getTotal();
            $invoicePaid  += ($invoice->getTotal() - $invoice->getDue());
            $invoiceDue   += $invoice->getDue();
        }

        $invoiceDetail['invoiceTotal'] = $invoiceTotal;
        $invoiceDetail['invoicePaid']  = $invoicePaid;
        $invoiceDetail['invoiceDue']   = $invoiceDue;

        return $invoiceDetail;
    }

    public function weeklyBill()
    {
        $staticstart = date('Y-m-d', strtotime('last Week'));
        $currentDate = date('Y-m-d');
        $bills       = Bill:: select('*')->where('created_by', \Auth::user()->creatorId())->where('bill_date', '>=', $staticstart)->where('bill_date', '<=', $currentDate)->get();
        $billTotal   = 0;
        $billPaid    = 0;
        $billDue     = 0;
        foreach($bills as $bill)
        {
            $billTotal += $bill->getTotal();
            $billPaid  += ($bill->getTotal() - $bill->getDue());
            $billDue   += $bill->getDue();
        }

        $billDetail['billTotal'] = $billTotal;
        $billDetail['billPaid']  = $billPaid;
        $billDetail['billDue']   = $billDue;

        return $billDetail;
    }

    public function monthlyBill()
    {
        $staticstart = date('Y-m-d', strtotime('last Month'));
        $currentDate = date('Y-m-d');
        $bills       = Bill:: select('*')->where('created_by', \Auth::user()->creatorId())->where('bill_date', '>=', $staticstart)->where('bill_date', '<=', $currentDate)->get();
        $billTotal   = 0;
        $billPaid    = 0;
        $billDue     = 0;
        foreach($bills as $bill)
        {
            $billTotal += $bill->getTotal();
            $billPaid  += ($bill->getTotal() - $bill->getDue());
            $billDue   += $bill->getDue();
        }

        $billDetail['billTotal'] = $billTotal;
        $billDetail['billPaid']  = $billPaid;
        $billDetail['billDue']   = $billDue;

        return $billDetail;
    }

    public function clientEstimations()
    {
        return $this->hasMany('App\Estimation', 'client_id', 'id');
    }

    public function clientContracts()
    {
        return $this->hasMany('App\Contract', 'client_name', 'id');
    }
    public function deals()
    {
        return $this->belongsToMany('App\Deal', 'user_deals', 'user_id', 'deal_id');
    }

    public function leads()
    {
        return $this->belongsToMany('App\Lead', 'user_leads', 'user_id', 'lead_id');
    }

    public function clientDeals()
    {
        return $this->belongsToMany('App\Deal', 'client_deals', 'client_id', 'deal_id');
    }

    public function employeeIdFormat($number)
    {
        $settings = Utility::settings();

        return $settings["employee_prefix"] . sprintf("%05d", $number);
    }

    public function getBranch($branch_id)
    {
        $branch = Branch::where('id', '=', $branch_id)->first();

        return $branch;
    }
    public function getDepartment($department_id)
    {
        $department = Department::where('id', '=', $department_id)->first();

        return $department;
    }

    public function getDesignation($designation_id)
    {
        $designation = Designation::where('id', '=', $designation_id)->first();

        return $designation;
    }

    public function getEmployee($employee)
    {
        $employee = Employee::where('id', '=', $employee)->first();

        return $employee;
    }

    public function getLeaveType($leave_type)
    {
        $leavetype = LeaveType::where('id', '=', $leave_type)->first();

        return $leavetype;
    }

    public function projects()
    {
        return $this->belongsToMany('App\Project', 'project_users', 'user_id', 'project_id')->withTimestamps();
    }

    // check project is shared or not
    public function checkProject($project_id)
    {
        $user_projects = $this->projects()->pluck('project_id')->toArray();
        if(array_key_exists($project_id, $user_projects))
        {
            $projectstatus = $user_projects[$project_id] == 'owner' ? 'Owner' : 'Shared';
        }

        return 'Owner';
    }
    // Make new attribute for directly get image
    public function getImgImageAttribute()
    {
      $userDetail = Employee::where('user_id',$this->id)->first();
       if(!empty($userDetail))
       {
         if(!empty($userDetail->avatar))
           return asset(\Storage::url($userDetail->avatar));
          else {
            return asset(\Storage::url('avatar.png'));
          }
       }
       else
       {
           return asset(\Storage::url('avatar.png'));
       }
    }
    // Get task users
    public function tasks()
    {
        return ProjectTask::whereRaw("find_in_set('" . $this->id . "',assign_to)")->get();
    }
    public function bugNumberFormat($number)
    {
        $settings = Utility::settings();

        return $settings["bug_prefix"] . sprintf("%05d", $number);
    }
    // Get User's Contact
    public function contacts()
    {
        return $this->hasMany('App\UserContact', 'parent_id', 'id');
    }
    public function todo()
    {
        return $this->hasMany('App\UserToDo', 'user_id', 'id');
    }
    public function employee()
    {
        return $this->hasOne('App\Employee', 'user_id', 'id');
    }
    public function total_lead()
    {
        if(\Auth::user()->type == 'company')
        {
            return Lead::where('created_by', '=', $this->creatorId())->count();
        }
        elseif(\Auth::user()->type == 'client')
        {
            return Lead::where('client', '=', $this->authId())->count();
        }
        else
        {
            return Lead::where('owner', '=', $this->authId())->count();
        }
    }
    public function last_projectstage()
    {
        return TaskStage::where('created_by', '=', $this->creatorId())->orderBy('order', 'DESC')->first();
    }
    public function user_project()
    {
        if(\Auth::user()->type != 'client')
        {
            return $this->belongsToMany('App\Project', 'project_users', 'user_id', 'project_id')->count();
        }
        else
        {
            return Project::where('client_id', '=', $this->authId())->count();
        }
    }
    public function created_total_project_task()
    {
        if(\Auth::user()->type == 'company')
        {
            return ProjectTask::join('projects', 'projects.id', '=', 'project_tasks.project_id')->where('projects.created_by', '=', $this->creatorId())->count();
        }
        elseif(\Auth::user()->type == 'client')
        {
            return ProjectTask::join('projects', 'projects.id', '=', 'project_tasks.project_id')->where('projects.client_id', '=', $this->authId())->count();
        }
        else
        {
            return ProjectTask::select('project_tasks.*', 'project_users.id as up_id')->join('project_users', 'project_users.project_id', '=', 'project_tasks.project_id')->where('project_users.user_id', '=', $this->authId())->count();
        }

    }
    public function project_complete_task($project_last_stage)
    {
        if(\Auth::user()->type == 'company')
        {
            return ProjectTask::join('projects', 'projects.id', '=', 'project_tasks.project_id')->where('projects.created_by', '=', $this->creatorId())->where('project_tasks.stage_id', '=', $project_last_stage)->count();
        }
        elseif(\Auth::user()->type == 'client')
        {
            $user_projects = Project::where('client_id',\Auth::user()->id)->pluck('id','id')->toArray();
            return ProjectTask::whereIn('project_id', $user_projects)->join('projects', 'projects.id', '=', 'project_tasks.project_id')->where('project_tasks.stage_id', '=', $project_last_stage)->count();
        }
        else
        {
            return ProjectTask::select('project_tasks.*', 'project_users.id as up_id')->join('project_users', 'project_users.project_id', '=', 'project_tasks.project_id')->where('project_users.user_id', '=', $this->authId())->where('project_tasks.stage_id', '=', $project_last_stage)->count();
        }
    }
    public function created_top_due_task()
    {
        if(\Auth::user()->type == 'company')
        {
            return ProjectTask::select('projects.*', 'project_tasks.id as task_id', 'project_tasks.name', 'project_tasks.end_date as task_due_date', 'project_tasks.assign_to', 'projectstages.name as stage_name')->join('projects', 'projects.id', '=', 'project_tasks.project_id')->join('projectstages', 'project_tasks.stage_id', '=', 'projectstages.id')
            ->where('projects.created_by', '=', \Auth::user()->creatorId())->where('project_tasks.end_date', '>', date('Y-m-d'))->limit(5)->orderBy('task_due_date', 'ASC')->get();
        }
        elseif(\Auth::user()->type == 'client')
        {
          $user_projects = Project::where('client_id',\Auth::user()->id)->pluck('id','id')->toArray();
          return ProjectTask::whereIn('project_id', $user_projects)->join('projects', 'projects.id', '=', 'project_tasks.project_id')->where('project_tasks.end_date', '>', date('Y-m-d'))->limit(5)->get();
        }
        else
        {
            return ProjectTask::select('project_tasks.*', 'project_tasks.end_date as task_due_date', 'project_users.id as up_id', 'projects.project_name as project_name', 'projectstages.name as stage_name')->join('project_users', 'project_users.project_id', '=', 'project_tasks.project_id')->join('projects', 'project_users.project_id', '=', 'projects.id')->join('projectstages', 'project_tasks.stage_id', '=', 'projectstages.id')
            ->where('project_users.user_id', '=', $this->authId())->where('project_tasks.end_date', '>', date('Y-m-d'))->limit(5)->orderBy(
                'project_tasks.end_date', 'ASC'
            )->get();
        }
    }

    public static function show_crm()
    {
      $user_type = \Auth::user()->type;
      if($user_type == 'company' || $user_type == 'super admin')
        $user = User::where('id',\Auth::user()->id)->first();
      else
        $user = User::where('id',\Auth::user()->created_by)->first();
      return Plan::find($user->plan)->crm;
    }
    public static function show_hrm()
    {
      $user_type = \Auth::user()->type;
      if($user_type == 'company' || $user_type == 'super admin'){
        $user = User::where('id',\Auth::user()->id)->first();
      }
      else{
        $user = User::where('id',\Auth::user()->created_by)->first();
      }

      return Plan::find($user->plan)->hrm;
    }
    public static function show_account()
    {
      $user_type = \Auth::user()->type;
      if($user_type == 'company' || $user_type == 'super admin')
        $user = User::where('id',\Auth::user()->id)->first();
      else
        $user = User::where('id',\Auth::user()->created_by)->first();
      return Plan::find($user->plan)->account;
    }
    public static function show_project()
    {
      $user_type = \Auth::user()->type;
      if($user_type == 'company' || $user_type == 'super admin')
        $user = User::where('id',\Auth::user()->id)->first();
      else
        $user = User::where('id',\Auth::user()->created_by)->first();
      return Plan::find($user->plan)->project;
    }
    public function clientProjects()
    {
        return $this->hasMany('App\Project', 'client_id', 'id');
    }

}
