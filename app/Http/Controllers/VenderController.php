<?php

namespace App\Http\Controllers;

use App\CustomField;
use App\Mail\UserCreate;
use App\Transaction;
use App\Vender;
use Auth;
use App\User;
use App\Plan;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class VenderController extends Controller
{

    public function dashboard()
    {
        $data['billChartData'] = \Auth::user()->billChartData();

        return view('vender.dashboard', $data);
    }

    public function index()
    {
        if(\Auth::user()->can('manage vender'))
        {
            $venders = Vender::where('created_by', \Auth::user()->creatorId())->get();

            return view('vender.index', compact('venders'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if(\Auth::user()->can('create vender'))
        {
            $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'vendor')->get();

            return view('vender.create', compact('customFields'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function store(Request $request)
    {
        if(\Auth::user()->can('create vender'))
        {
            $rules = [
                'name' => 'required',
                'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
                'email' => 'required|email|unique:venders',
            ];


            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('vender.index')->with('error', $messages->first());
            }


                        $objVendor    = \Auth::user();
                        $creator      = User::find($objVendor->creatorId());
                        $total_vendor = $objVendor->countVenders();
                        $plan         = Plan::find($creator->plan);

                        $default_language = DB::table('settings')->select('value')->where('name', 'default_language')->first();
                        if($total_vendor < $plan->max_venders || $plan->max_venders == -1)
                        {
                            $vender                   = new Vender();
                            $vender->vender_id        = $this->venderNumber();
                            $vender->name             = $request->name;
                            $vender->contact          = $request->contact;
                            $vender->email            = $request->email;
                            $vender->password         = Hash::make($request->password);
                            $vender->created_by       = \Auth::user()->creatorId();
                            $vender->billing_name     = $request->billing_name;
                            $vender->billing_country  = $request->billing_country;
                            $vender->billing_state    = $request->billing_state;
                            $vender->billing_city     = $request->billing_city;
                            $vender->billing_phone    = $request->billing_phone;
                            $vender->billing_zip      = $request->billing_zip;
                            $vender->billing_address  = $request->billing_address;
                            $vender->shipping_name    = $request->shipping_name;
                            $vender->shipping_country = $request->shipping_country;
                            $vender->shipping_state   = $request->shipping_state;
                            $vender->shipping_city    = $request->shipping_city;
                            $vender->shipping_phone   = $request->shipping_phone;
                            $vender->shipping_zip     = $request->shipping_zip;
                            $vender->shipping_address = $request->shipping_address;
                            $vender->lang             = !empty($default_language) ? $default_language->value : '';
                            $vender->save();
                            CustomField::saveData($vender, $request->customField);
                        }
                        else
                        {
                            return redirect()->back()->with('error', __('Your user limit is over, Please upgrade plan.'));
                        }

            try
            {

                Mail::to($vender->email)->send(new UserCreate($vender));
            }
            catch(\Exception $e)
            {
                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            }

            return redirect()->route('vender.index')->with('success', __('Vendor successfully created.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function show($ids)
    {
        $id     = \Crypt::decrypt($ids);
        $vendor = Vender::find($id);

        return view('vender.show', compact('vendor'));
    }


    public function edit($id)
    {
        if(\Auth::user()->can('edit vender'))
        {
            $vender              = Vender::find($id);
            $vender->customField = CustomField::getData($vender, 'vendor');

            $customFields = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'vendor')->get();

            return view('vender.edit', compact('vender', 'customFields'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function update(Request $request, Vender $vender)
    {
        if(\Auth::user()->can('edit vender'))
        {

            $rules = [
                'name' => 'required',
                'contact' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/',
            ];


            $validator = \Validator::make($request->all(), $rules);

            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->route('vender.index')->with('error', $messages->first());
            }

            $vender->name             = $request->name;
            $vender->contact          = $request->contact;
            $vender->created_by       = \Auth::user()->creatorId();
            $vender->billing_name     = $request->billing_name;
            $vender->billing_country  = $request->billing_country;
            $vender->billing_state    = $request->billing_state;
            $vender->billing_city     = $request->billing_city;
            $vender->billing_phone    = $request->billing_phone;
            $vender->billing_zip      = $request->billing_zip;
            $vender->billing_address  = $request->billing_address;
            $vender->shipping_name    = $request->shipping_name;
            $vender->shipping_country = $request->shipping_country;
            $vender->shipping_state   = $request->shipping_state;
            $vender->shipping_city    = $request->shipping_city;
            $vender->shipping_phone   = $request->shipping_phone;
            $vender->shipping_zip     = $request->shipping_zip;
            $vender->shipping_address = $request->shipping_address;
            $vender->save();
            CustomField::saveData($vender, $request->customField);

            return redirect()->route('vender.index')->with('success', __('Vendor successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Vender $vender)
    {
        if(\Auth::user()->can('delete vender'))
        {
            if($vender->created_by == \Auth::user()->creatorId())
            {
                $vender->delete();

                return redirect()->route('vender.index')->with('success', __('Vendor successfully deleted.'));
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

    function venderNumber()
    {
        $latest = Vender::where('created_by', '=', \Auth::user()->creatorId())->latest()->first();
        if(!$latest)
        {
            return 1;
        }

        return $latest->vender_id + 1;
    }

    public function venderLogout(Request $request)
    {
        \Auth::guard('vender')->logout();

        $request->session()->invalidate();

        return redirect()->route('vender.login');
    }

    public function payment(Request $request)
    {

        if(\Auth::user()->can('manage vender payment'))
        {
            $category = [
                'Bill' => 'Bill',
                'Deposit' => 'Deposit',
                'Sales' => 'Sales',
            ];

            $query = Transaction::where('user_id', \Auth::user()->id)->where('created_by', \Auth::user()->creatorId())->where('user_type', 'Vender')->where('type', 'Payment');
            if(!empty($request->date))
            {
                $date_range = explode(' - ', $request->date);
                $query->whereBetween('date', $date_range);
            }

            if(!empty($request->category))
            {
                $query->where('category', '=', $request->category);
            }
            $payments = $query->get();


            return view('vender.payment', compact('payments', 'category'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function transaction(Request $request)
    {

        if(\Auth::user()->can('manage vender transaction'))
        {


            $category = [
                'Bill' => 'Bill',
                'Deposit' => 'Deposit',
                'Sales' => 'Sales',
            ];

            $query = Transaction::where('user_id', \Auth::user()->id)->where('user_type', 'Vender');

            if(!empty($request->date))
            {
                $date_range = explode(' - ', $request->date);
                $query->whereBetween('date', $date_range);
            }

            if(!empty($request->category))
            {
                $query->where('category', '=', $request->category);
            }
            $transactions = $query->get();


            return view('vender.transaction', compact('transactions', 'category'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function profile()
    {
        $userDetail              = \Auth::user();
        $userDetail->customField = CustomField::getData($userDetail, 'vendor');
        $customFields            = CustomField::where('created_by', '=', \Auth::user()->creatorId())->where('module', '=', 'vendor')->get();

        return view('vender.profile', compact('userDetail', 'customFields'));
    }

    public function editprofile(Request $request)
    {

        $userDetail = \Auth::user();
        $user       = Vender::findOrFail($userDetail['id']);
        $this->validate(
            $request, [
                        'name' => 'required|max:120',
                        'contact' => 'required',
                        'email' => 'required|email|unique:users,email,' . $userDetail['id'],
                    ]
        );
        if($request->hasFile('profile'))
        {
            $filenameWithExt = $request->file('profile')->getClientOriginalName();
            $filename        = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension       = $request->file('profile')->getClientOriginalExtension();
            $fileNameToStore = $filename . '_' . time() . '.' . $extension;

            $dir        = storage_path('uploads/avatar/');
            $image_path = $dir . $userDetail['avatar'];

            if(File::exists($image_path))
            {
                File::delete($image_path);
            }

            if(!file_exists($dir))
            {
                mkdir($dir, 0777, true);
            }

            $path = $request->file('profile')->storeAs('uploads/avatar/', $fileNameToStore);

        }

        if(!empty($request->profile))
        {
            $user['avatar'] = $fileNameToStore;
        }
        $user['name']    = $request['name'];
        $user['email']   = $request['email'];
        $user['contact'] = $request['contact'];
        $user->save();
        CustomField::saveData($user, $request->customField);

        return redirect()->back()->with(
            'success', 'Profile successfully updated.'
        );
    }

    public function editBilling(Request $request)
    {

        $userDetail = \Auth::user();
        $user       = Vender::findOrFail($userDetail['id']);
        $this->validate(
            $request, [
                        'billing_name' => 'required',
                        'billing_country' => 'required',
                        'billing_state' => 'required',
                        'billing_city' => 'required',
                        'billing_phone' => 'required',
                        'billing_zip' => 'required',
                        'billing_address' => 'required',
                    ]
        );
        $input = $request->all();
        $user->fill($input)->save();

        return redirect()->back()->with(
            'success', 'Profile successfully updated.'
        );
    }

    public function editShipping(Request $request)
    {
        $userDetail = \Auth::user();
        $user       = Vender::findOrFail($userDetail['id']);
        $this->validate(
            $request, [
                        'shipping_name' => 'required',
                        'shipping_country' => 'required',
                        'shipping_state' => 'required',
                        'shipping_city' => 'required',
                        'shipping_phone' => 'required',
                        'shipping_zip' => 'required',
                        'shipping_address' => 'required',
                    ]
        );
        $input = $request->all();
        $user->fill($input)->save();

        return redirect()->back()->with(
            'success', 'Profile successfully updated.'
        );
    }

    public function updatePassword(Request $request)
    {

        if(Auth::Check())
        {
            $request->validate(
                [
                    'current_password' => 'required',
                    'new_password' => 'required|min:6',
                    'confirm_password' => 'required|same:new_password',
                ]
            );
            $objUser          = Auth::user();
            $request_data     = $request->All();
            $current_password = $objUser->password;
            if(Hash::check($request_data['current_password'], $current_password))
            {
                $user_id            = Auth::User()->id;
                $obj_user           = Vender::find($user_id);
                $obj_user->password = Hash::make($request_data['new_password']);;
                $obj_user->save();

                return redirect()->back()->with('success', __('Password successfully updated.'));
            }
            else
            {
                return redirect()->back()->with('error', __('Please enter correct current password.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Something is wrong.'));
        }

    }

    public function changeLanquage($lang)
    {


        $user       = Auth::user();
        $user->lang = $lang;
        $user->save();

        return redirect()->back()->with('success', __('Language successfully change.'));


    }
}
