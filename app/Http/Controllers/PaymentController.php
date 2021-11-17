<?php

namespace App\Http\Controllers;

use App\BankAccount;
use App\BillPayment;
use App\Mail\BillPaymentCreate;
use App\Mail\SendWorkspaceInvication;
use App\Payment;
use App\ProductServiceCategory;
use App\Transaction;
use App\Utility;
use App\Vender;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class PaymentController extends Controller
{

    public function index(Request $request)
    {
        if(\Auth::user()->can('manage payment'))
        {
            $vender = Vender::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $vender->prepend('All', '');

            $account = BankAccount::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('holder_name', 'id');
            $account->prepend('All', '');

            $category = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 2)->get()->pluck('name', 'id');
            $category->prepend('All', '');


            $query = Payment::where('created_by', '=', \Auth::user()->creatorId());

            if(!empty($request->date))
            {
                $date_range = explode(' - ', $request->date);
                $query->whereBetween('date', $date_range);
            }

            if(!empty($request->vender))
            {
                $query->where('id', '=', $request->vender);
            }
            if(!empty($request->account))
            {
                $query->where('account_id', '=', $request->account);
            }

            if(!empty($request->category))
            {
                $query->where('category_id', '=', $request->category);
            }


            $payments = $query->get();


            return view('payment.index', compact('payments', 'account', 'category', 'vender'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function create()
    {
        if(\Auth::user()->can('create payment'))
        {
            $venders = Vender::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $venders->prepend('--', 0);
            $categories = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->where('type', '=', 2)->get()->pluck('name', 'id');
            $accounts   = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('payment.create', compact('venders', 'categories', 'accounts'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function store(Request $request)
    {

        if(\Auth::user()->can('create payment'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'amount' => 'required',
                                   'account_id' => 'required',
                                   'category_id' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }

            $payment                 = new Payment();
            $payment->date           = $request->date;
            $payment->amount         = $request->amount;
            $payment->account_id     = $request->account_id;
            $payment->vender_id      = $request->vender_id;
            $payment->category_id    = $request->category_id;
            $payment->payment_method = 0;
            $payment->reference      = $request->reference;
            $payment->description    = $request->description;
            $payment->created_by     = \Auth::user()->creatorId();
            $payment->save();

            $category            = ProductServiceCategory::where('id', $request->category_id)->first();
            $payment->payment_id = $payment->id;
            $payment->type       = 'Payment';
            $payment->category   = $category->name;
            $payment->user_id    = $payment->vender_id;
            $payment->user_type  = 'Vender';
            $payment->account    = $request->account_id;

            Transaction::addTransaction($payment);

            $vender          = Vender::where('id', $request->vender_id)->first();
            $payment         = new BillPayment();
            $payment->name   = $vender['name'];
            $payment->method = '-';
            $payment->date   = \Auth::user()->dateFormat($request->date);
            $payment->amount = \Auth::user()->priceFormat($request->amount);
            $payment->bill   = '';

            if(!empty($vender))
            {
                Utility::userBalance('vendor', $vender->id, $request->amount, 'debit');
            }

            Utility::bankAccountBalance($request->account_id, $request->amount, 'debit');

            try
            {
                Mail::to($vender['email'])->send(new BillPaymentCreate($payment));
            }
            catch(\Exception $e)
            {
                $smtp_error = __('E-Mail has been not sent due to SMTP configuration');
            }

            return redirect()->route('payment.index')->with('success', __('Payment successfully created.') . ((isset($smtp_error)) ? '<br> <span class="text-danger">' . $smtp_error . '</span>' : ''));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }

    public function edit(Payment $payment)
    {

        if(\Auth::user()->can('edit payment'))
        {
            $venders = Vender::where('created_by', '=', \Auth::user()->creatorId())->get()->pluck('name', 'id');
            $venders->prepend('--', 0);
            $categories = ProductServiceCategory::where('created_by', '=', \Auth::user()->creatorId())->get()->where('type', '=', 2)->pluck('name', 'id');

            $accounts = BankAccount::select('*', \DB::raw("CONCAT(bank_name,' ',holder_name) AS name"))->where('created_by', \Auth::user()->creatorId())->get()->pluck('name', 'id');

            return view('payment.edit', compact('venders', 'categories', 'accounts', 'payment'));
        }
        else
        {
            return response()->json(['error' => __('Permission denied.')], 401);
        }
    }


    public function update(Request $request, Payment $payment)
    {
        if(\Auth::user()->can('edit payment'))
        {

            $validator = \Validator::make(
                $request->all(), [
                                   'date' => 'required',
                                   'amount' => 'required',
                                   'account_id' => 'required',
                                   'vender_id' => 'required',
                                   'category_id' => 'required',
                               ]
            );
            if($validator->fails())
            {
                $messages = $validator->getMessageBag();

                return redirect()->back()->with('error', $messages->first());
            }
            $vender = Vender::where('id', $request->vender_id)->first();
            if(!empty($vender))
            {
                Utility::userBalance('vendor', $vender->id, $payment->amount, 'credit');
            }
            Utility::bankAccountBalance($payment->account_id, $payment->amount, 'credit');

            $payment->date           = $request->date;
            $payment->amount         = $request->amount;
            $payment->account_id     = $request->account_id;
            $payment->vender_id      = $request->vender_id;
            $payment->category_id    = $request->category_id;
            $payment->payment_method = 0;
            $payment->reference      = $request->reference;
            $payment->description    = $request->description;
            $payment->save();

            $category            = ProductServiceCategory::where('id', $request->category_id)->first();
            $payment->category   = $category->name;
            $payment->payment_id = $payment->id;
            $payment->type       = 'Payment';
            $payment->account    = $request->account_id;
            Transaction::editTransaction($payment);

            if(!empty($vender))
            {
                Utility::userBalance('vendor', $vender->id, $request->amount, 'debit');
            }

            Utility::bankAccountBalance($request->account_id, $request->amount, 'debit');

            return redirect()->route('payment.index')->with('success', __('Payment successfully updated.'));
        }
        else
        {
            return redirect()->back()->with('error', __('Permission denied.'));
        }
    }


    public function destroy(Payment $payment)
    {
        if(\Auth::user()->can('delete payment'))
        {
            if($payment->created_by == \Auth::user()->creatorId())
            {
                $payment->delete();
                $type = 'Payment';
                $user = 'Vender';
                Transaction::destroyTransaction($payment->id, $type, $user);

                if($payment->vender_id != 0)
                {
                    Utility::userBalance('vendor', $payment->vender_id, $payment->amount, 'credit');
                }
                Utility::bankAccountBalance($payment->account_id, $payment->amount, 'credit');

                return redirect()->route('payment.index')->with('success', __('Payment successfully deleted.'));
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
