<?php

namespace App\Http\Controllers;

use App\ClientDeal;
use App\ClientPermission;
use App\Mail\UserCreate;
use App\Contract;
use App\CustomField;
use App\Estimation;
use App\Invoice;
use App\Plan;
use App\User;
use App\Utility;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Spatie\Permission\Models\Role;

class ClientController extends Controller
{
    public function __construct()
    {
        $this->middleware(
            [
                'auth',
                'XSS',
            ]
        );
    }

    public function index()
    {
        if(\Auth::user()->can('manage client'))
        {
            $user    = \Auth::user();
            $clients = User::where('created_by', '=', $user->creatorId())->where('type', '=', 'client')->get();

            return view('clients.index', compact('clients'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function create(Request $request)
    {

        if(\Auth::user()->can('create client'))
        {
            if($request->ajax)
            {
                return view('clients.createAjax');
            }
            else
            {
                $customFields = CustomField::where('module', '=', 'client')->get();

                return view('clients.create', compact('customFields'));
            }
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function store(Request $request)
    {
                if(\Auth::user()->can('create client'))
                {
                    $user      = \Auth::user();
                    $validator = \Validator::make(
                        $request->all(), [
                                           'name' => 'required',
                                           'email' => 'required|email|unique:users',
                                           'password' => 'required',
                                       ]
                    );

                    if($validator->fails())
                    {
                        $messages = $validator->getMessageBag();
                        if($request->ajax)
                        {
                            return response()->json(['error' => $messages->first()], 401);
                        }
                        else
                        {
                            return redirect()->back()->with('error', $messages->first());
                        }
                    }
                    $objCustomer    = \Auth::user();
                    $creator        = User::find($objCustomer->creatorId());
                    $total_client = User::where('type','client')->count();
                    // dd($total_client);
                    $plan           = Plan::find($creator->plan);
                    if($total_client < $plan->max_clients || $plan->max_clients == -1)
                    {
                        $role = Role::findByName('client');
                        $client = User::create(
                            [
                                'name' => $request->name,
                                'email' => $request->email,
                                'job_title' => $request->job_title,
                                'password' => Hash::make($request->password),
                                'type' => 'client',
                                'lang' => Utility::getValByName('default_language'),
                                'created_by' => $user->creatorId(),
                            ]
                        );
                        try
                        {
                            Mail::to($client->email)->send(new UserCreate($client));
                        }
                        catch(\Exception $e)
                        {

                            $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
                        }

                        return redirect()->route('clients.index')->with('success', __('Client successfully added.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));

                    }

                       /* $client->assignRole($role);
                        $uArr = [
                            'email' => $client->email,
                            'password' => $request->password,
                        ];
                        // Send Email
                        $resp = Utility::sendEmailTemplate('New User', [$client->id => $client->email], $uArr);


                        if($request->customField)
                        {
                            CustomField::saveData($client, $request->customField);
                        }
                        if($request->ajax)
                        {
                            return response()->json(
                                [
                                    'success' => __('Client created Successfully!'),
                                    'record' => $client,
                                    'target' => '#client_id',
                                ], 200
                            );
                        }
                        else
                        {
                            return redirect()->back()->with('success', __('Client created Successfully!') . (($resp['is_success'] == false && !empty($resp['error'])) ? '<br> <span class="text-danger">' . $resp['error'] . '</span>' : ''));
                        }*/

                    else
                    {
                        return redirect()->back()->with('error', __('Your user limit is over, Please upgrade plan.'));
                    }

                }
                else
                {
                    if($request->ajax)
                    {
                        return response()->json(['error' => __('Permission Denied.')], 401);
                    }
                    else
                    {
                        return redirect()->back()->with('error', __('Permission Denied.'));
                    }
                }
    }

    public function show(User $client)
    {
        $usr = Auth::user();
        if(!empty($client) && $usr->id == $client->creatorId() && $client->id != $usr->id && $client->type == 'client')
        {
            // For Estimations
            $estimations = $client->clientEstimations()->orderByDesc('id')->get();
            $curr_month  = $client->clientEstimations()->whereMonth('issue_date', '=', date('m'))->get();
            $curr_week   = $client->clientEstimations()->whereBetween(
                'issue_date', [
                                \Carbon\Carbon::now()->startOfWeek(),
                                \Carbon\Carbon::now()->endOfWeek(),
                            ]
            )->get();
            $last_30days = $client->clientEstimations()->whereDate('issue_date', '>', \Carbon\Carbon::now()->subDays(30))->get();
            // Estimation Summary
            $cnt_estimation                = [];
            $cnt_estimation['total']       = Estimation::getEstimationSummary($estimations);
            $cnt_estimation['this_month']  = Estimation::getEstimationSummary($curr_month);
            $cnt_estimation['this_week']   = Estimation::getEstimationSummary($curr_week);
            $cnt_estimation['last_30days'] = Estimation::getEstimationSummary($last_30days);

            $cnt_estimation['cnt_total']       = $estimations->count();
            $cnt_estimation['cnt_this_month']  = $curr_month->count();
            $cnt_estimation['cnt_this_week']   = $curr_week->count();
            $cnt_estimation['cnt_last_30days'] = $last_30days->count();

            // For Contracts
            $contracts   = $client->clientContracts()->orderByDesc('id')->get();
            $curr_month  = $client->clientContracts()->whereMonth('start_date', '=', date('m'))->get();
            $curr_week   = $client->clientContracts()->whereBetween(
                'start_date', [
                                \Carbon\Carbon::now()->startOfWeek(),
                                \Carbon\Carbon::now()->endOfWeek(),
                            ]
            )->get();
            $last_30days = $client->clientContracts()->whereDate('start_date', '>', \Carbon\Carbon::now()->subDays(30))->get();

            // Contracts Summary
            $cnt_contract                = [];
            $cnt_contract['total']       = Contract::getContractSummary($contracts);
            $cnt_contract['this_month']  = Contract::getContractSummary($curr_month);
            $cnt_contract['this_week']   = Contract::getContractSummary($curr_week);
            $cnt_contract['last_30days'] = Contract::getContractSummary($last_30days);

            $cnt_contract['cnt_total']       = $contracts->count();
            $cnt_contract['cnt_this_month']  = $curr_month->count();
            $cnt_contract['cnt_this_week']   = $curr_week->count();
            $cnt_contract['cnt_last_30days'] = $last_30days->count();

            return view('clients.show', compact('client', 'estimations', 'cnt_estimation', 'contracts', 'cnt_contract'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function edit(User $client)
    {
        if(\Auth::user()->can('edit client'))
        {
            $user = \Auth::user();
            if($client->created_by == $user->creatorId())
            {
                $client->customField = CustomField::getData($client, 'client');
                $customFields        = CustomField::where('module', '=', 'client')->get();

                return view('clients.edit', compact('client', 'customFields'));
            }
            else
            {
                return response()->json(['error' => __('Invalid Client.')], 401);
            }
        }
        else
        {
            return response()->json(['error' => __('Permission Denied.')], 401);
        }
    }

    public function update(User $client, Request $request)
    {
        if(\Auth::user()->can('edit client'))
        {
            $user = \Auth::user();
            if($client->created_by == $user->creatorId())
            {
                $validation = [
                    'name' => 'required',
                    'email' => 'required|email|unique:users,email,' . $client->id,
                ];

                $post         = [];
                $post['name'] = $request->name;
                if(!empty($request->password))
                {
                    $validation['password'] = 'required';
                    $post['password']       = Hash::make($request->password);
                }

                $validator = \Validator::make($request->all(), $validation);
                if($validator->fails())
                {
                    $messages = $validator->getMessageBag();

                    return redirect()->back()->with('error', $messages->first());
                }
                $post['email'] = $request->email;

                $client->update($post);

                CustomField::saveData($client, $request->customField);

                return redirect()->back()->with('success', __('Client Updated Successfully!'));
            }
            else
            {
                return redirect()->back()->with('error', __('Invalid Client.'));
            }
        }
        else
        {
            return redirect()->back()->with('error', __('Permission Denied.'));
        }
    }

    public function destroy(User $client)
    {
        $user = \Auth::user();
            if($client->created_by == $user->creatorId())
            {
                $estimation = Estimation::where('client_id', '=', $client->id)->first();
                if(empty($estimation))
                {
                  /*  ClientDeal::where('client_id', '=', $client->id)->delete();
                    ClientPermission::where('client_id', '=', $client->id)->delete();*/
                    $client->delete();
                    return redirect()->back()->with('success', __('Client Deleted Successfully!'));
                }
                else
                {
                    return redirect()->back()->with('error', __('This client has assigned some estimation.'));
                }
            }
            else
            {
                return redirect()->back()->with('error', __('Invalid Client.'));
            }
        }


}
