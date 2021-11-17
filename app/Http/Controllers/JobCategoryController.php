<?php

namespace App\Http\Controllers;

use App\JobCategory;
use Illuminate\Http\Request;

class JobCategoryController extends Controller
{

    public function index()
    {
        if(\Auth::user()->can('manage job category'))
        {
            $categories = JobCategory::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('jobCategory.index', compact('categories'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        return view('jobCategory.create');
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create job category'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $jobCategory             = new JobCategory();
            $jobCategory->title      = $request->title;
            $jobCategory->created_by = \Auth::user()->creatorId();
            $jobCategory->save();

            return redirect()->back()->with('success', __('Job category  successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function show(JobCategory $jobCategory)
    {
        //
    }


    public function edit(JobCategory $jobCategory)
    {
        return view('jobCategory.edit', compact('jobCategory'));
    }


    public function update(Request $request, JobCategory $jobCategory)
    {
        if(\Auth::user()->can('edit job category'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'title' => 'required',
                               ]
            );

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $jobCategory->title = $request->title;
            $jobCategory->save();

            return redirect()->back()->with('success', __('Job category  successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(JobCategory $jobCategory)
    {
        if(\Auth::user()->can('delete job category'))
        {
            if($jobCategory->created_by == \Auth::user()->creatorId())
            {
                $jobCategory->delete();

                return redirect()->back()->with('success', __('Job category successfully deleted.'));
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
