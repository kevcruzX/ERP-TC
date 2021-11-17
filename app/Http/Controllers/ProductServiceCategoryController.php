<?php

namespace App\Http\Controllers;

use App\Bill;
use App\Invoice;
use App\ProductService;
use App\ProductServiceCategory;
use Illuminate\Http\Request;

class ProductServiceCategoryController extends Controller
{
    public function index()
    {
        if(\Auth::user()->can('manage constant category'))
        {
            $categories = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->get();

            return view('productServiceCategory.index', compact('categories'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if(\Auth::user()->can('create constant category'))
        {
            $types = ProductServiceCategory::$categoryType;

            return view('productServiceCategory.create', compact('types'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }

    public function store(Request $request)
    {
        if(\Auth::user()->can('create constant category'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'name' => 'required|max:20',
                                   'type' => 'required',
                                   'color' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $category             = new ProductServiceCategory();
            $category->name       = $request->name;
            $category->color      = $request->color;
            $category->type       = $request->type;
            $category->created_by = \Auth::user()->creatorId();
            $category->save();

            return redirect()->route('product-category.index')->with('success', __('Category successfully created.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function edit($id)
    {

        if(\Auth::user()->can('edit constant category'))
        {
            $types    = ProductServiceCategory::$categoryType;
            $category = ProductServiceCategory::find($id);

            return view('productServiceCategory.edit', compact('category', 'types'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function update(Request $request, $id)
    {
        if(\Auth::user()->can('edit constant category'))
        {
            $category = ProductServiceCategory::find($id);
            if($category->created_by == \Auth::user()->creatorId())
            {
                $validator = \Validator::make(
                    $request->all(), [
                                       'name' => 'required|max:20',
                                       'type' => 'required',
                                       'color' => 'required',
                                   ]
                );
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }

                $category->name  = $request->name;
                $category->color = $request->color;
                $category->type  = $request->type;
                $category->save();

                return redirect()->route('product-category.index')->with('success', __('Category successfully updated.'));
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

    public function destroy($id)
    {
        if(\Auth::user()->can('delete constant category'))
        {
            $category = ProductServiceCategory::find($id);
            if($category->created_by == \Auth::user()->creatorId())
            {

                if($category->type == 0)
                {
                    $categories = ProductService::where('category_id', $category->id)->first();
                }
                elseif($category->type == 1)
                {
                    $categories = Invoice::where('category_id', $category->id)->first();
                }
                else
                {
                    $categories = Bill::where('category_id', $category->id)->first();
                }

                if(!empty($categories))
                {
                    return redirect()->back()->with('error', __('this category is already assign so please move or remove this category related data.'));
                }

                $category->delete();

                return redirect()->route('product-category.index')->with('success', __('Category successfully deleted.'));
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
