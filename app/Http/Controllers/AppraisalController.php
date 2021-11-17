<?php

namespace App\Http\Controllers;

use App\Appraisal;
use App\Branch;
use App\Competencies;
use App\Employee;
use Illuminate\Http\Request;

class AppraisalController extends Controller
{

    public function index()
    {
        if(\Auth::user()->can('manage appraisal'))
        {
            $user = \Auth::user();
            if($user->type == 'employee')
            {
                $employee   = Employee::where('user_id', $user->id)->first();
                $appraisals = Appraisal::where('created_by', '=', \Auth::user()->creatorId())->where('branch', $employee->branch_id)->where('employee', $employee->id)->get();
            }
            else
            {
                $appraisals = Appraisal::where('created_by', '=', \Auth::user()->creatorId())->get();
            }

            return view('appraisal.index', compact('appraisals'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if(\Auth::user()->can('create appraisal'))
        {
            $technicals      = Competencies::where('created_by', \Auth::user()->creatorId())->where('type', 'technical')->get();
            $organizationals = Competencies::where('created_by', \Auth::user()->creatorId())->where('type', 'organizational')->get();
            $behaviourals = Competencies::where('created_by', \Auth::user()->creatorId())->where('type', 'behavioural')->get();

            $brances = Branch::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $brances->prepend('Select Branch', '');

            return view('appraisal.create', compact( 'brances', 'technicals', 'organizationals','behaviourals'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {

        if(\Auth::user()->can('create appraisal'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'branch' => 'required',
                                   'employee' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $appraisal                 = new Appraisal();
            $appraisal->branch         = $request->branch;
            $appraisal->employee       = $request->employee;
            $appraisal->appraisal_date = $request->appraisal_date;
            $appraisal->rating         = json_encode($request->rating, true);
            $appraisal->remark         = $request->remark;
            $appraisal->created_by     = \Auth::user()->creatorId();
            $appraisal->save();

            return redirect()->route('appraisal.index')->with('success', __('Appraisal successfully created.'));
        }
    }

    public function show(Appraisal $appraisal)
    {
        $ratings = json_decode($appraisal->rating, true);

        $technicals      = Competencies::where('created_by', \Auth::user()->creatorId())->where('type', 'technical')->get();
        $organizationals = Competencies::where('created_by', \Auth::user()->creatorId())->where('type', 'organizational')->get();
        $behaviourals = Competencies::where('created_by', \Auth::user()->creatorId())->where('type', 'behavioural')->get();

        return view('appraisal.show', compact('appraisal', 'technicals', 'organizationals', 'ratings','behaviourals'));
    }



    public function edit(Appraisal $appraisal)
    {
        if(\Auth::user()->can('edit appraisal'))
        {
            $technicals      = Competencies::where('created_by', \Auth::user()->creatorId())->where('type', 'technical')->get();
            $organizationals = Competencies::where('created_by', \Auth::user()->creatorId())->where('type', 'organizational')->get();
            $behaviourals = Competencies::where('created_by', \Auth::user()->creatorId())->where('type', 'behavioural')->get();

            $brances = Branch::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $brances->prepend('Select Branch', '');

            $ratings = json_decode($appraisal->rating,true);


            return view('appraisal.edit', compact( 'brances', 'appraisal', 'technicals', 'organizationals','ratings','behaviourals'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, Appraisal $appraisal)
    {
        if(\Auth::user()->can('edit appraisal'))
        {
            $validator = \Validator::make(
                $request->all(), [
                                   'branch' => 'required',
                                   'employee' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $appraisal->branch         = $request->branch;
            $appraisal->employee       = $request->employee;
            $appraisal->appraisal_date = $request->appraisal_date;
            $appraisal->rating         = json_encode($request->rating, true);
            $appraisal->remark         = $request->remark;
            $appraisal->save();

            return redirect()->route('appraisal.index')->with('success', __('Appraisal successfully updated.'));
        }
    }
    public function destroy(Appraisal $appraisal)
    {
        if(\Auth::user()->can('delete appraisal'))
        {
            if($appraisal->created_by == \Auth::user()->creatorId())
            {
                $appraisal->delete();

                return redirect()->route('appraisal.index')->with('success', __('Appraisal successfully deleted.'));
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
}
