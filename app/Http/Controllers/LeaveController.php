<?php

namespace App\Http\Controllers;

use App\Employee;
use App\Leave;
use App\LeaveType;
use App\Mail\LeaveActionSend;
use App\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class LeaveController extends Controller
{
    public function index()
    {

        if(\Auth::user()->can('manage leave'))
        {
            $leaves = Leave::where('created_by', '=', \Auth::user()->creatorId())->get();
            if(\Auth::user()->type == 'employee')
            {
                $user     = \Auth::user();
                $employee = Employee::where('user_id', '=', $user->id)->first();
                $leaves   = Leave::where('employee_id', '=', $employee->id)->get();
            }
            else
            {
                $leaves = Leave::where('created_by', '=', \Auth::user()->creatorId())->get();
            }

            return view('leave.index', compact('leaves'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function create()
    {
        if(\Auth::user()->can('create leave'))
        {
            if(Auth::user()->type == 'employee')
            {
                $employees = Employee::where('user_id', '=', \Auth::user()->id)->get()->pluck('name', 'id');
            }
            else
            {
                $employees = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            }
            $leavetypes      = LeaveType::where('created_by', '=', \Auth::user()->creatorId())->get();
            $leavetypes_days = LeaveType::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('leave.create', compact('employees', 'leavetypes', 'leavetypes_days'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {

        if(\Auth::user()->can('create leave'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'leave_type_id' => 'required',
                                   'start_date' => 'required',
                                   'end_date' => 'required',
                                   'leave_reason' => 'required',
                                   'remark' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }


            $employee = Employee::where('user_id', '=', Auth::user()->id)->first();
            $leave    = new Leave();
            if(\Auth::user()->type == "employee")
            {
                $leave->employee_id = $employee->id;
            }
            else
            {
                $leave->employee_id = $request->employee_id;
            }
            $leave->leave_type_id    = $request->leave_type_id;
            $leave->applied_on       = date('Y-m-d');
            $leave->start_date       = $request->start_date;
            $leave->end_date         = $request->end_date;
            $leave->total_leave_days = 0;
            $leave->leave_reason     = $request->leave_reason;
            $leave->remark           = $request->remark;
            $leave->status           = 'Pending';
            $leave->created_by       = \Auth::user()->creatorId();

            $leave->save();

            return redirect()->route('leave.index')->with('success', __('Leave  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(Leave $leave)
    {
        return redirect()->route('leave.index');
    }

    public function edit(Leave $leave)
    {
        if(\Auth::user()->can('edit leave'))
        {
            if($leave->created_by == \Auth::user()->creatorId())
            {
                $employees  = Employee::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
                $leavetypes = LeaveType::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('title', 'id');

                return view('leave.edit', compact('leave', 'employees', 'leavetypes'));
            }
            else
            {
                return response()->json(['error' => __('Permission denied.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function update(Request $request, $leave)
    {

        $leave = Leave::find($leave);
        if(\Auth::user()->can('edit leave'))
        {
            if($leave->created_by == Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'leave_type_id' => 'required',
                                       'start_date' => 'required',
                                       'end_date' => 'required',
                                       'leave_reason' => 'required',
                                       'remark' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $leave->employee_id      = $request->employee_id;
                $leave->leave_type_id    = $request->leave_type_id;
                $leave->start_date       = $request->start_date;
                $leave->end_date         = $request->end_date;
                $leave->total_leave_days = 0;
                $leave->leave_reason     = $request->leave_reason;
                $leave->remark           = $request->remark;

                $leave->save();

                return redirect()->route('leave.index')->with('success', __('Leave successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function destroy(Leave $leave)
    {
        if(\Auth::user()->can('delete leave'))
        {
            if($leave->created_by == \Auth::user()->creatorId())
            {
                $leave->delete();

                return redirect()->route('leave.index')->with('success', __('Leave successfully deleted.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Permission denied.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function action($id)
    {
        $leave     = Leave::find($id);
        $employee  = Employee::find($leave->employee_id);
        $leavetype = LeaveType::find($leave->leave_type_id);

        return view('leave.action', compact('employee', 'leavetype', 'leave'));
    }

    public function changeaction(Request $request)
    {

        $leave = Leave::find($request->leave_id);

        $leave->status = $request->status;
        if($leave->status == 'Approval')
        {
            $startDate               = new \DateTime($leave->start_date);
            $endDate                 = new \DateTime($leave->end_date);
            $total_leave_days        = $startDate->diff($endDate)->days;
            $leave->total_leave_days = $total_leave_days;
            $leave->status           = 'Approve';
        }

        $leave->save();

        $setings = Utility::settings();
        if($setings['leave_status'] == 1)
        {
            $employee     = Employee::where('id', $leave->employee_id)->where('created_by', '=', \Auth::user()->creatorId())->first();
            $leave->name  = !empty($employee->name) ? $employee->name : '';
            $leave->email = !empty($employee->email) ? $employee->email : '';
            try
            {
                Mail::to($leave->email)->send(new LeaveActionSend($leave));
            }
            catch(\Exception $e)
            {
                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            }

            return redirect()->route('leave.index')->with('success', __('Leave status successfully updated.') . (isset($smtp_error) ? $smtp_error : ''));

        }

        return redirect()->route('leave.index')->with('success', __('Leave status successfully updated.'));
    }

    public function jsoncount(Request $request)
    {

        $leave_counts = LeaveType::select(\DB::raw('COALESCE(SUM(leaves.total_leave_days),0) AS total_leave, leave_types.title, leave_types.days,leave_types.id'))
                                 ->leftjoin('leaves', function ($join) use ($request){
            $join->on('leaves.leave_type_id', '=', 'leave_types.id');
            $join->where('leaves.employee_id', '=', $request->employee_id);
        }
        )->groupBy('leaves.leave_type_id')->get();

        return $leave_counts;

    }
}
